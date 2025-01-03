<?php

namespace App\Http\Controllers\API\Instructor;

use App\Http\Controllers\Controller;
use App\Http\Requests\API\Courses\StoreCourseRequest;
use App\Models\Course;
use App\Traits\LoggableTrait;
use App\Traits\UploadToCloudinaryTrait;
use App\Traits\UploadToMuxTrait;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class CourseController extends Controller
{
    use LoggableTrait, UploadToCloudinaryTrait, UploadToMuxTrait;

    public function store(StoreCourseRequest $request)
    {
        $data = $request->validated();
        $data['user_id'] = 4;
        $data['code'] = Str::uuid();
        $data['slug'] = !empty($data['name']) ? Str::slug($data['name']) . '-' . $data['code'] : null;

        try {
            $course = Course::query()->create($data);

            return $this->respondCreated('Tạo khoá học thành công', [
                'course' => $course->load('category'),
            ]);
        } catch (\Exception $e) {
            $this->logError($e);

            return $this->respondServerError('Có lỗi xảy ra, vui lòng thử lại',
                Response::HTTP_INTERNAL_SERVER_ERROR
            );
        }
    }
}
