<?php

namespace App\Http\Controllers\API\Instructor;

use App\Http\Controllers\Controller;
use App\Http\Requests\API\Courses\StoreCourseRequest;
use App\Http\Requests\API\Courses\UpdateContentCourse;
use App\Models\Course;
use App\Traits\LoggableTrait;
use App\Traits\UploadToCloudinaryTrait;
use App\Traits\UploadToMuxTrait;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class CourseController extends Controller
{
    use LoggableTrait, UploadToCloudinaryTrait, UploadToMuxTrait;

    const FOLDER_COURSE_THUMBNAIL = 'courses/thumbnail';
    const FOLDER_COURSE_INTRO = 'courses/intro';

    public function index(Request $request)
    {
        try {
            $query = $request->input('q');

            $courses = Course::query()
                ->where('user_id', Auth::id())
                ->select([
                    'id', 'category_id', 'name', 'slug', 'thumbnail',
                    'intro', 'price', 'price_sale', 'total_student',
                ])
                ->with([
                    'category:id,name,slug,parent_id',
                ])
                ->search($query)
                ->orderBy('created_at')
                ->get();

            if ($courses->isEmpty()) {
                return $this->respondNotFound('Không tìm thấy khoá học');
            }

            return $this->respondOk('Danh sách khoá học của: ' . Auth::user()->name,
                $courses
            );
        } catch (\Exception $e) {
            $this->logError($e);

            return $this->respondServerError('Có lỗi xảy ra, vui lòng thử lại',
                Response::HTTP_INTERNAL_SERVER_ERROR
            );
        }
    }

    public function getCourseOverView(string $slug)
    {
        try {
            $course = Course::query()
                ->where('slug', $slug)
                ->select([
                    'id', 'user_id', 'category_id', 'name', 'slug', 'thumbnail',
                    'intro', 'price', 'price_sale', 'description',
                    'level', 'total_student', 'requirements', 'benefits', 'qa'
                ])
                ->with([
                    'user:id,name,email,avatar,created_at',
                    'category:id,name,slug,parent_id',
                    'chapters:id,course_id,title,order',
                    'chapters.lessons'
                ])
                ->first();

            if (!$course) {
                return $this->respondNotFound('Không tìm thấy khoá học');
            }

            return $this->respondOk('Thông tin khoá học: ' . $course->name,
                $course
            );
        } catch (\Exception $e) {

            $this->logError($e);
            return $this->respondServerError('Có lỗi xảy ra, vui lòng thử lại',
                Response::HTTP_INTERNAL_SERVER_ERROR
            );
        }
    }

    public function store(StoreCourseRequest $request)
    {
        try {
            $data = $request->validated();

            $data['user_id'] = Auth::id();

            do {
                $data['code'] = (string)Str::uuid();
                $exits = Course::query()->where('code', $data['code'])->exists();
            } while ($exits);

            $data['slug'] = !empty($data['name'])
                ? Str::slug($data['name']) . '-' . $data['code']
                : $data['code'];

            $course = Course::query()->create($data);

            return $this->respondCreated('Tạo khoá học thành công', [
                'course' => $course->load('category'),
            ]);
        } catch (\Exception $e) {
            $this->logError($e, $request->all());

            return $this->respondServerError('Có lỗi xảy ra, vui lòng thử lại',
                Response::HTTP_INTERNAL_SERVER_ERROR
            );
        }
    }

    public function updateContentCourse(UpdateContentCourse $request, string $slug)
    {
        try {
            DB::beginTransaction();

            $data = $request->validated();

            $course = Course::query()
                ->where('slug', $slug)
                ->first();

            $thumbnailOld = $course->thumbnail ?? null;
            $introOld = $course->intro ?? null;

            if (!$course) {
                return $this->respondNotFound('Không tìm thấy khoá học');
            }

            $data['slug'] = !empty($data['name'])
                ? Str::slug($data['name']) . '-' . $course->code
                : $course->slug;

            $data['thumbnail'] = $request->hasFile('thumbnail')
                ? $this->handleFileUpload(
                    $request->file('thumbnail'),
                    $thumbnailOld,
                    self::FOLDER_COURSE_THUMBNAIL,
                    'image')
                : $thumbnailOld;

            $data['intro'] = $request->hasFile('intro')
                ? $this->handleFileUpload(
                    $request->file('intro'),
                    $introOld,
                    self::FOLDER_COURSE_INTRO,
                    'video')
                : $introOld;

            $data['requirements'] = !empty($request->input('requirements'))
                ? json_encode($request->input('requirements'))
                : $course->requirements;
            $data['benefits'] = !empty($request->input('benefits'))
                ? json_encode($request->input('benefits'))
                : $course->benefits;
            $data['qa'] = !empty($request->input('qa'))
                ? json_encode($request->input('qa'))
                : $course->qa;

            $course->update($data);

            DB::commit();

            return $this->respondOk('Thao tác thành công',
                $course->load('category')
            );
        } catch (\Exception $e) {
            DB::rollBack();

            $this->rollbackFileUploads($data, $thumbnailOld, $introOld);

            $this->logError($e, $request->all());

            if ($e instanceof ValidationException) {
                return $this->respondFailedValidation('Dữ liệu không hợp lệ', $e->errors());
            }

            return $this->respondServerError('Có lỗi xảy ra, vui lòng thử lại',
                Response::HTTP_INTERNAL_SERVER_ERROR
            );
        }
    }

    private function handleFileUpload($newFile, $oldFile, $folder, $type)
    {
        $uploadFile = $type === 'image'
            ? $this->uploadImage($newFile, $folder)
            : $this->uploadVideo($newFile, $folder);

        if (!empty($oldFile) && filter_var($oldFile, FILTER_VALIDATE_URL)) {
            $type === 'image'
                ? $this->deleteImage($oldFile, $folder)
                : $this->deleteVideo($oldFile, $folder);
        }

        return $uploadFile;
    }

    private function rollbackFileUploads(array $data, $thumbnailOld, $introOld)
    {
        if (!empty($data['thumbnail']) && filter_var($data['thumbnail'], FILTER_VALIDATE_URL)) {
            $this->deleteImage($data['thumbnail'], self::FOLDER_COURSE_THUMBNAIL);
        }

        if (!empty($data['intro']) && filter_var($data['intro'], FILTER_VALIDATE_URL)) {
            $this->deleteVideo($data['intro'], self::FOLDER_COURSE_INTRO);
        }

        $this->deleteFileIfValid($thumbnailOld, self::FOLDER_COURSE_THUMBNAIL, 'image');
        $this->deleteFileIfValid($introOld, self::FOLDER_COURSE_INTRO, 'video');
    }

    private function deleteFileIfValid($file, $folder, $type)
    {
        if (!empty($file) && filter_var($file, FILTER_VALIDATE_URL)) {
            $type === 'image' ? $this->deleteImage($file, $folder) : $this->deleteVideo($file, $folder);
        }
    }

    public function deleteCourse(string $slug)
    {
        try {
            $course = Course::query()
                ->where('slug', $slug)
                ->first();

            if (!$course) {
                return $this->respondNotFound('Không tìm thấy khoá học');
            }

            if ($course->chapters()->count() > 0) {
                return $this->respondError('Khoá học đang chứa chương học, không thể xóa');
            }

            $course->delete();

            return $this->respondOk('Xóa khoá học thành công');
        } catch (\Exception $e) {
            $this->logError($e);

            return $this->respondServerError('Có lỗi xảy ra, vui lòng thử lại',
                Response::HTTP_INTERNAL_SERVER_ERROR
            );
        }
    }

    public function getChapters(string $slug)
    {
        try {
            $course = Course::query()
                ->where('slug', $slug)
                ->first();

            if (!$course) {
                return $this->respondNotFound('Không tìm thấy khoá học');
            }

            $chapters = $course->chapters()
                ->select([
                    'id', 'course_id', 'title', 'slug', 'order'
                ])
                ->orderBy('order')
                ->get();

            return $this->respondOk('Danh sách chương học của khoá học: ' . $course->name,
                $chapters
            );
        } catch (\Exception $e) {
            $this->logError($e);

            return $this->respondServerError('Có lỗi xảy ra, vui lòng thử lại',
                Response::HTTP_INTERNAL_SERVER_ERROR
            );
        }
    }
}
