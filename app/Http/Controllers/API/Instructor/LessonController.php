<?php

namespace App\Http\Controllers\API\Instructor;

use App\Http\Controllers\Controller;
use App\Http\Requests\API\Lessons\StoreLessonRequest;
use App\Http\Requests\API\Lessons\UpdateLessonRequest;
use App\Models\Answer;
use App\Models\Coding;
use App\Models\Document;
use App\Models\Lesson;
use App\Models\Question;
use App\Models\Quiz;
use App\Models\Video;
use App\Services\VideoUploadService;
use App\Traits\LoggableTrait;
use App\Traits\UploadToCloudinaryTrait;
use App\Traits\UploadToLocalTrait;
use F9Web\ApiResponseHelpers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class LessonController extends Controller
{
    use LoggableTrait, ApiResponseHelpers, UploadToCloudinaryTrait, UploadToLocalTrait;

    const VIDEO_LESSON = 'videos/lessons';
    const DOCUMENT_LESSON = 'documents/lessons';

    const QUIZ_LESSON = 'quizzes';

    protected $videoUploadService;

    public function __construct(VideoUploadService $videoUploadService)
    {
        $this->videoUploadService = $videoUploadService;
    }

    public function storeLesson(StoreLessonRequest $request)
    {
        try {
            $data = $request->validated();
            $data['slug'] = !empty($data['title']) ? Str::slug($data['title']) : Str::uuid();

            $lessonable = $this->updateOrCreateLessonable($data);
            $lesson = $this->createLesson($data, $lessonable);

            return $this->respondCreated([
                'message' => 'Tạo bài học thành công', 
                'data' => $lesson->load('lessonable')
            ]);
        } catch (\Exception $e) {
            $this->logError($e, $request->all());

            return $this->respondServerError('Có lỗi xảy ra, vui lòng thử lại');
        }
    }

    public function updateContentLesson(UpdateLessonRequest $request, string $slug)
    {
        try {
            DB::beginTransaction();

            $data = $request->validated();

            $lesson = $this->getLessonBySlug($slug);

            if (!$lesson) {
                return $this->respondNotFound('Không tìm thấy bài học');
            }

            $data['slug'] = !empty($data['title']) ? Str::slug($data['title']) : $lesson->slug;

            $lessonable = $this->handleLessonTypeUpdate($request, $lesson, $data);

            $lesson->update([
                'chapter_id' => $data['chapter_id'] ?? $lesson->chapter_id,
                'title' => $data['title'] ?? $lesson->title,
                'slug' => $data['slug'],
                'type' => $data['type'] ?? $lesson->type,
                'is_free_preview' => $data['is_free_preview'] ?? $lesson->is_free_preview,
                'content' => $data['content'] ?? $lesson->content,
                'lessonable_type' => $lessonable->getMorphClass(),
                'lessonable_id' => $lessonable->id,
            ]);

            DB::commit();

            return $this->respondOk('Cập nhật bài học thành công',
                $lesson->load('lessonable')
            );
        } catch (\Exception $e) {
            DB::rollBack();

            if (isset($filePath) && Storage::exists($filePath)) {
                $this->deleteFromLocal($filePath, self::DOCUMENT_LESSON);
            }

            $this->logError($e, $request->all());

            return $this->respondServerError('Có lỗi xảy ra, vui lòng thử lại');
        }
    }

    public function deleteLesson(string $slug)
    {
        try {
            $lesson = $this->getLessonBySlug($slug);

            if (!$lesson) {
                return $this->respondNotFound('Không tìm thấy bài học');
            }

            $lessonable = $lesson->lessonable;
            $this->deleteLessonable($lessonable);
            $lesson->delete();

            return $this->respondNoContent();
        } catch (\Exception $e) {
            $this->logError($e);

            return $this->respondServerError('Có lỗi xảy ra, vui lòng thử lại');
        }
    }

    private function getLessonBySlug(string $slug)
    {
        return Lesson::query()->where('slug', $slug)->first();
    }

    private function handleLessonTypeUpdate($request, $lesson, $data)
    {
        $lessonable = $lesson->lessonable;

        if ($request->type && $lesson->lessonable_type !== $this->getLessonableType($request->type)) {
            $this->deleteLessonable($lessonable);
            $lessonable = $this->updateOrCreateLessonable($data);
        }

        switch ($data['type']) {
            case 'video':
                $this->updateVideoContent($request, $lessonable);
                break;
            case 'document':
                $this->updateDocumentContent($request, $lessonable);
                break;
            case 'coding':
                $this->updateCodingContent($request, $lessonable);
                break;
            case 'quiz':
                $this->updateQuizContent($request, $lessonable);
                break;
        }

        return $lessonable;
    }

    private function updateOrCreateLessonable(array $data)
    {
        $lesson = $this->getLessonBySlug($data['slug']);

        switch ($data['type']) {
            case 'video':
                return Video::query()->updateOrCreate([
                    'title' => ($data['title'] ?? $lesson->title) . '-' . Str::uuid(),
                    'type' => 'upload',
                ]);

            case 'document':
                return Document::query()->updateOrCreate([
                    'title' => ($data['title'] ?? $lesson->title) . '-' . Str::uuid(),
                ]);

            case 'quiz':
                return Quiz::query()->updateOrCreate([
                    'title' => ($data['title'] ?? $lesson->title) . '-' . Str::uuid(),
                ]);

            case 'coding':
                return Coding::query()->updateOrCreate([
                    'title' => ($data['title'] ?? $lesson->title) . '-' . Str::uuid(),
                ]);

            default:
                throw new \InvalidArgumentException('Loại bài học không hợp lệ');
        }
    }

    private function createLesson(array $data, $lessonable)
    {
        return Lesson::create([
            'chapter_id' => $data['chapter_id'],
            'title' => $data['title'],
            'slug' => $data['slug'],
            'type' => $data['type'],
            'lessonable_type' => $lessonable->getMorphClass(),
            'lessonable_id' => $lessonable->id,
        ]);
    }

    private function deleteLessonable($lessonable)
    {
        if ($lessonable instanceof Video) {
            $this->deleteVideo($lessonable->public_id);
        } elseif ($lessonable instanceof Document) {
            if ($lessonable->file_path && Storage::exists($lessonable->file_path)) {
                $this->deleteFromLocal($lessonable->file_path, self::DOCUMENT_LESSON);
            }
        } elseif ($lessonable instanceof Quiz) {
            foreach ($lessonable->questions as $question) {
                $question->answers()->delete();
                $question->delete();
            }

            if ($lessonable->image) {
                $this->deleteImage($lessonable->image, self::QUIZ_LESSON);
            }
        }

        $lessonable->delete();
    }

    private function updateVideoContent(Request $request, Video $video)
    {
        $video->update([
            'title' => $request->title,
        ]);

        if ($request->hasFile('video_file')) {
            $dataFile = $this->uploadVideo($request->file('video_file'), self::VIDEO_LESSON, true);
            $muxVideoUrl = $this->videoUploadService->uploadVideoToMux($dataFile['secure_url']);

            $video->update([
                'url' => $dataFile['secure_url'],
                'mux_playback_id' => $muxVideoUrl,
                'duration' => $dataFile['duration'],
            ]);
        }
    }

    private function updateQuizContent(Request $request, Quiz $quiz)
    {
        $quiz->update(['title' => $request->title]);

        if (isset($request->questions) && is_array($request->questions)) {
            foreach ($request->questions as $questionData) {
                $questionImagePath = null;

                if (isset($questionData['image']) && $questionData['image']->isValid()) {
                    $questionImagePath = $this->uploadImage($questionData['image'], self::QUIZ_LESSON);
                }

                $question = Question::updateOrCreate(
                    ['id' => $questionData['id'] ?? null, 'quiz_id' => $quiz->id],
                    [
                        'question' => $questionData['question'],
                        'image' => $questionImagePath,
                        'answer_type' => $questionData['answer_type'] ?? null,
                        'description' => $questionData['description'] ?? null,
                    ]
                );

                if (isset($questionData['answers']) && is_array($questionData['answers'])) {
                    foreach ($questionData['answers'] as $answerData) {
                        $answer = new Answer();
                        $answer->question_id = $question->id;
                        $answer->answer = $answerData['answer'];
                        $answer->is_correct = $answerData['is_correct'] ?? false;
                        $answer->save();
                    }
                }
            }
        }
    }

    private function updateCodingContent(Request $request, Coding $coding)
    {
        $coding->update([
            'language' => $request->language,
            'hints' => $request->hints,
            'sample_code' => $this->getSampleCode($request->language),
            'result_code' => $request->result_code,
            'solution_code' => $request->solution_code,
        ]);
    }

    private function updateDocumentContent(Request $request, Document $document)
    {
        $document->update([
            'title' => $request->title,
        ]);

        if ($request->hasFile('document_file')) {
            $documentFile = $request->file('document_file');
            $filePath = $this->uploadToLocal($documentFile, self::DOCUMENT_LESSON);

            $document->update([
                'file_path' => $filePath,
                'file_type' => 'upload',
            ]);
        } elseif (!empty($request->document_url)) {
            $document->update([
                'file_path' => $request->document_url,
                'file_type' => 'url',
            ]);
        }
    }

    private function getLessonableType(string $type)
    {
        return match ($type) {
            'video' => Video::class,
            'document' => Document::class,
            'quiz' => Quiz::class,
            'coding' => Coding::class,
            default => throw new \InvalidArgumentException('Loại bài học không hợp lệ'),
        };
    }

    private function getSampleCode($language)
    {
        return match ($language) {
            'php' => "<?php echo 'Hello, world!';",
            'javascript' => "console.log('Hello, world!');",
            'python' => "print('Hello, world!')",
            'java' => "public class Main {\n    public static void main(String[] args) {\n        System.out.println(\"Hello, world!\");\n    }\n}",
            'typescript' => "console.log('Hello, world!');",
            default => throw new \InvalidArgumentException("Không hỗ trợ ngôn ngữ: $language"),
        };
    }
}
