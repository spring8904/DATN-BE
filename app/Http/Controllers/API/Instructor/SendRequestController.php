<?php

namespace App\Http\Controllers\API\Instructor;

use App\Http\Controllers\Controller;
use App\Mail\CourseSubmitMail;
use App\Models\Approvable;
use App\Models\Course;
use App\Models\User;
use App\Traits\LoggableTrait;
use F9Web\ApiResponseHelpers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class SendRequestController extends Controller
{
    use LoggableTrait, ApiResponseHelpers;

    public function submitCourse(Request $request, string $slug)
    {
        try {
            $course = Course::query()->where('slug', $slug)->first();

            if (!$course) {
                return $this->respondNotFound('Khóa học không tồn tại');
            }

            $status = $request->status;

            $approvable = Approvable::where('approvable_id', $course->id)
                ->where('approvable_type', Course::class)
                ->first();

            if (!$approvable) {
                $approvable = new Approvable();
                $approvable->approvable_id = $course->id;
                $approvable->approvable_type = Course::class;
                $approvable->status = 'pending';
                $approvable->request_date = now();
                $approvable->save();
            }

            switch ($status) {
                case 'draft':
                    $course->update([
                        'status' => 'pending',
                    ]);

                    Mail::to($course->user->email)->send(new CourseSubmitMail($course));

                    break;
            }

        } catch (\Exception $e) {
            $this->logError($e, $request->all());

            return $this->respondServerError('Có lỗi xảy ra, vui lòng thử lại sau');
        }
    }


}
