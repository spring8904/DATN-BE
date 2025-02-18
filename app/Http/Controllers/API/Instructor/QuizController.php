<?php

namespace App\Http\Controllers\API\Instructor;

use App\Http\Controllers\Controller;
use App\Http\Requests\API\Lessons\StoreQuizLessonRequest;
use App\Models\Chapter;
use App\Models\Lesson;
use App\Models\Quiz;
use App\Traits\ApiResponseTrait;
use App\Traits\LoggableTrait;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class QuizController extends Controller
{
    use LoggableTrait, ApiResponseTrait;

    public function storeLessonQuiz(StoreQuizLessonRequest $request, string $chapterId)
    {
        try {
            DB::beginTransaction();

            $data = $request->all();

            $data['slug'] = !empty($data['title'])
                ? Str::slug($data['title']) . '-' . Str::uuid()
                : Str::uuid();

            $chapter = Chapter::query()->where('id', $chapterId)->first();

            if (!$chapter) {
                return $this->respondNotFound('Không tìm thấy chương học');
            }

            if ($chapter->course->user_id !== auth()->id()) {
                return $this->respondForbidden('Bạn không có quyền thực hiện thao tác này');
            }

            $data['order'] = $chapter->lessons->max('order') + 1;

            $quiz = Quiz::query()->create([
                'title' => $data['title'],
            ]);

            $lesson = Lesson::query()->create([
                'chapter_id' => $chapter->id,
                'title' => $data['title'],
                'slug' => $data['slug'],
                'type' => 'video',
                'lessonable_type' => Quiz::class,
                'lessonable_id' => $quiz->id,
                'order' => $data['order'],
                'is_free_preview' => $data['is_free_preview'] ?? false,
            ]);

            DB::commit();

            return $this->respondCreated('Tạo bài ôn tập thành công', $lesson->load('lessonable'));
        } catch (\Exception $e) {
            DB::rollBack();

            $this->logError($e);
            return $this->respondServerError('Không thể tạo câu hỏi, vui lòng thử lại');
        }
    }

    public function downloadQuizForm()
    {
        try {
            $file = public_path('storage/csv/QuizFormCourseMeLy.csv');

            if (!file_exists($file)) {
                return $this->respondNotFound('Không tìm thấy file mẫu');
            }

            return response()->download($file, 'QuizFormCourseMeLy.csv');
        } catch (\Exception $e) {
            $this->logError($e);

            return $this->respondServerError('Có lỗi xảy ra, vui lòng thử lại sau');
        }
    }
}
