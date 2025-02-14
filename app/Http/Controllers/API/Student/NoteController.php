<?php

namespace App\Http\Controllers\API\Student;

use App\Http\Controllers\Controller;
use App\Http\Requests\API\Note\StoreNoteRequest;
use App\Http\Requests\API\Note\UpdateNoteRequest;
use App\Models\Course;
use App\Models\CourseUser;
use App\Models\Lesson;
use App\Models\Note;
use App\Traits\ApiResponseTrait;
use App\Traits\LoggableTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NoteController extends Controller
{
    use LoggableTrait, ApiResponseTrait;

    public function index(Request $request, string $courseId)
    {
        try {
            $user = Auth::user();

            if (!$user) {
                return $this->respondForbidden('Bạn không có quyền thực hiện thao tác này');
            }

            $hasPurchased = $this->hashPurchasedCourse($user->id, $courseId);

            if (!$hasPurchased) {
                return $this->respondForbidden('Bạn chưa mua khoá học này');
            }

            $course = Course::with([
                'chapters.lessons.notes' => function ($query) use ($user) {
                    $query->where('user_id', $user->id);
                }
            ])->find($courseId);

            if (!$course) {
                return $this->respondNotFound('Không tìm thấy khoá học');
            }

            $query = Note::query()->where('user_id', $user->id)
                ->whereHas('lesson.chapter.course', function ($q) use ($courseId) {
                    $q->where('id', $courseId);
                });

            if ($request->filled('lesson_id')) {
                $query->where('lesson_id', $request->lesson_id);
            }

            if ($request->filled('time_min') && $request->filled('time_max')) {
                $query->whereBetween('time', [$request->time_min, $request->time_max]);
            }

            $notes = $query->paginate(10);

            return $this->respondOk('Danh sách ghi chú của khoá học: ' . $course->name, $notes);
        } catch (\Exception $e) {
            $this->logError($e, $request->all());

            return $this->respondServerError('Internal Server Error');
        }
    }

    public function store(StoreNoteRequest $request)
    {
        try {
            $data = $request->validated();

            $user = Auth::user();

            if (!$user) {
                $this->respondForbidden('Bạn không có quyền thực hiện thao tác này');
            }

            $lesson = Lesson::query()
                ->with('chapter.course')
                ->find($data['lesson_id']);

            if (!$lesson) {
                return $this->respondNotFound('Không tìm thấy bài học');
            }

            $course = $this->hashPurchasedCourse($user->id, $lesson->chapter->course->id);

            if (!$course) {
                return $this->respondForbidden('Bạn chưa mua khoá học này');
            }

            $note = Note::query()->create([
                'user_id' => $user->id,
                'lesson_id' => $data['lesson_id'],
                'time' => $data['time'],
                'content' => $data['content'],
            ]);

            return $this->respondCreated('Tạo ghi chú thành công', $note);
        } catch (\Exception $e) {
            $this->LogError($e, $request->all());

            return $this->respondServerError('Internal Server Error');
        }
    }

    public function update(UpdateNoteRequest $request, string $id)
    {
        try {
            $data = $request->validated();

            $user = Auth::user();

            $note = Note::query()->find($id);

            if (!$note) {
                return $this->respondNotFound('Không tìm thấy ghi chú');
            }

            if ($note->user_id !== $user->id) {
                return $this->respondForbidden('Bạn không có quyền thực hiện thao tác này');
            }

            $note->update([
                'time' => $data['time'],
                'content' => $data['content'],
            ]);

            return $this->respondOk('Cập nhật ghi chú thành công', $note);
        } catch (\Exception $e) {
            $this->logError($e, $request->all());

            return $this->respondServerError('Internal Server Error');
        }
    }

    public function destroy($id)
    {
        try {
            $user = Auth::user();

            $note = Note::query()->find($id);

            if (!$note) {
                return $this->respondNotFound('Không tìm thấy ghi chú');
            }

            if ($note->user_id !== $user->id) {
                return $this->respondForbidden('Bạn không có quyền thực hiện thao tác này');
            }

            $note->delete();

            return $this->respondOk('Xóa ghi chú thành công');
        } catch (\Exception $e) {
            $this->logError($e);

            return $this->respondServerError('Internal Server Error');
        }
    }

    private function hashPurchasedCourse($userId, $courseId)
    {
        return CourseUser::query()
            ->where('user_id', $userId)
            ->where('course_id', $courseId)
            ->exists();
    }

}
