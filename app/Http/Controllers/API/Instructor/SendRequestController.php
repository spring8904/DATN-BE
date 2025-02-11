<?php

namespace App\Http\Controllers\API\Instructor;

use App\Http\Controllers\Controller;
use App\Mail\CourseSubmitMail;
use App\Models\Approvable;
use App\Models\Course;
use App\Models\User;
use App\Notifications\CourseSubmittedNotification;
use App\Traits\ApiResponseTrait;
use App\Traits\LoggableTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class SendRequestController extends Controller
{
    use LoggableTrait, ApiResponseTrait;

    public function submitCourse(Request $request, string $slug)
    {
        try {
            $course = Course::query()
                ->where('slug', $slug)
                ->first();

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
            }else {
                return $this->respondOk('Yêu cầu kiểm duyệt khoá học đã được gửi');
            }

            switch ($status) {
                case 'draft':
                    $course->update([
                        'status' => 'pending',
                    ]);

                    Mail::to($course->user->email)->send(new CourseSubmitMail($course));

                    $managers = User::query()->role([
                        'admin',
                    ])->get();

                    foreach ($managers as $manager) {
                        $manager->notify(new CourseSubmittedNotification($course->load('approvables')));
                    }

                    break;

//                case 'pending':
//                    $approvable->update([
//                        'status' => 'pending',
//                    ]);
//                    break;
//
//                case 'reject':
//                    $approvable->update([
//                        'status' => 'rejected',
//                    ]);
//                    break;
            }

            return $this->respondCreated('Gửi yêu cầu thành công');
        } catch (\Exception $e) {
            $this->logError($e, $request->all());

            return $this->respondServerError('Có lỗi xảy ra, vui lòng thử lại sau');
        }
    }

}
