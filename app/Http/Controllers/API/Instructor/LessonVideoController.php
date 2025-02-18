<?php

namespace App\Http\Controllers\API\Instructor;

use App\Http\Controllers\Controller;
use App\Http\Requests\API\Lessons\StoreLessonVideoRequest;
use App\Models\Chapter;
use App\Models\Coding;
use App\Models\Document;
use App\Models\Lesson;
use App\Models\Quiz;
use App\Models\Video;
use App\Services\VideoUploadService;
use App\Traits\ApiResponseTrait;
use App\Traits\LoggableTrait;
use App\Traits\UploadToCloudinaryTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class LessonVideoController extends Controller
{
    use LoggableTrait, ApiResponseTrait, UploadToCloudinaryTrait;

    const VIDE0_LESSON = 'videos/lessons';

    protected $videoUploadService;

    public function __construct(VideoUploadService $videoUploadService)
    {
        $this->videoUploadService = $videoUploadService;
    }

    public function storeLessonVideo(StoreLessonVideoRequest $request, string $chapterId)
    {
        try {
            $data = $request->validated();

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

            if ($request->hasFile('video_file')) {
                $dataFile = $this->uploadVideo($request->file('video_file'), self::VIDE0_LESSON, true);
                $muxVideoUrl = $this->videoUploadService->uploadVideoToMux($dataFile['secure_url']);

                $video = Video::query()->create([
                    'title' => $data['title'],
                    'url' => $dataFile['secure_url'],
                    'mux_playback_id' => $muxVideoUrl,
                    'duration' => $dataFile['duration'],
                ]);
            }

            $data['order'] = $chapter->lessons->max('order') + 1;

            $lesson = Lesson::query()->create([
                'chapter_id' => $chapter->id,
                'title' => $data['title'],
                'slug' => $data['slug'],
                'type' => 'video',
                'lessonable_type' => Video::class,
                'lessonable_id' => $video->id,
                'order' => $data['order'],
                'is_free_preview' => $data['is_free_preview'] ?? false,
            ]);

            return $this->respondCreated('Tạo bài giảng thành công', $lesson->load('lessonable'));
        } catch (\Exception $e) {
            $this->logError($e, $request->all());

            return $this->respondServerError('Có lỗi xảy ra, vui lòng thử lại sau');
        }
    }

}
