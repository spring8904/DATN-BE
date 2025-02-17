<?php

namespace App\Http\Controllers\API\Instructor;

use App\Http\Controllers\Controller;
use App\Http\Requests\API\Courses\StoreCourseRequest;
use App\Http\Requests\API\Courses\UpdateContentCourse;
use App\Http\Requests\API\Courses\UpdateCourseObjectives;
use App\Models\Course;
use App\Services\CourseValidatorService;
use App\Traits\ApiResponseTrait;
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
    use LoggableTrait, UploadToCloudinaryTrait, UploadToMuxTrait, ApiResponseTrait;

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
                    'chapters:id,course_id,title,order',
                    'chapters.lessons:id,chapter_id,title,slug,order'
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
            );
        }
    }

    public function getCourseOverView(string $slug)
    {
        try {
            $course = Course::query()
                ->where('slug', $slug)
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

            if ($course->user_id !== Auth::id()) {
                return $this->respondForbidden('Không có quyền thực hiện thao tác');
            }

            $course->benefits = is_string($course->benefits) ? json_decode($course->benefits, true) : $course->benefits;
            $course->requirements = is_string($course->requirements) ? json_decode($course->requirements, true) : $course->requirements;
            $course->qa = is_string($course->qa) ? json_decode($course->qa, true) : $course->qa;

            return $this->respondOk('Thông tin khoá học: ' . $course->name,
                $course
            );
        } catch (\Exception $e) {

            $this->logError($e);
            return $this->respondServerError('Có lỗi xảy ra, vui lòng thử lại',
            );
        }
    }

    public function store(StoreCourseRequest $request)
    {
        try {
            $data = $request->validated();

            $data['user_id'] = Auth::id();

            if ($data['user_id'] !== Auth::id()) {
                return $this->respondForbidden('Không có quyền thực hiện thao tác');
            }

            do {
                $data['code'] = (string)Str::uuid();
                $exits = Course::query()->where('code', $data['code'])->exists();
            } while ($exits);

            $data['slug'] = !empty($data['name'])
                ? Str::slug($data['name']) . '-' . $data['code']
                : $data['code'];

            $course = Course::query()->create([
                'user_id' => $data['user_id'],
                'category_id' => $data['category_id'],
                'code' => $data['code'],
                'name' => $data['name'],
                'slug' => $data['slug'],
                'benefits' => json_encode([]),
                'requirements' => json_encode([]),
                'qa' => json_encode([]),
            ]);

            return $this->respondCreated('Tạo khoá học thành công',
                $course->load('category')
            );
        } catch (\Exception $e) {
            $this->logError($e, $request->all());

            return $this->respondServerError('Có lỗi xảy ra, vui lòng thử lại');
        }
    }

    public function updateCourseOverView(UpdateContentCourse $request, string $slug)
    {
        try {
            DB::beginTransaction();

            $data = $request->validated();

            $course = Course::query()
                ->where('slug', $slug)
                ->first();

            if (!$course) {
                return $this->respondNotFound('Không tìm thấy khoá học');
            }

            if ($course->user_id !== auth()->id()) {
                return $this->respondForbidden('Không có quyền thực hiện thao tác');
            }

            $thumbnailOld = $course->thumbnail ?? null;
            $introOld = $course->intro ?? null;

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

            return $this->respondServerError('Có lỗi xảy ra, vui lòng thử lại');
        }
    }

    public function updateCourseObjectives(UpdateCourseObjectives $request, string $slug)
    {
        try {
            $data = $request->validated();

            $course = Course::query()
                ->where('slug', $slug)
                ->first();

            if (!$course) {
                return $this->respondNotFound('Không tìm thấy khoá học');
            }

            if ($course->user_id !== Auth::id()) {
                return $this->respondForbidden('Không có quyền thực hiện thao tác');
            }

            $data['requirements'] = array_key_exists('requirements', $data)
                ? (is_string($data['requirements']) ? json_decode($data['requirements'], true) : $data['requirements'])
                : $course->requirements;

            $data['benefits'] = array_key_exists('benefits', $data)
                ? (is_string($data['benefits']) ? json_decode($data['benefits'], true) : $data['benefits'])
                : $course->benefits;

            $data['qa'] = array_key_exists('qa', $data)
                ? (is_string($data['qa']) ? json_decode($data['qa'], true) : $data['qa'])
                : $course->qa;

            $course->update($data);

            return $this->respondOk('Cập nhật mục tiêu khoá học thành công',
                $course->load('category')
            );
        } catch (\Exception $e) {
            $this->logError($e, $request->all());

            return $this->respondServerError('Có lỗi xảy ra, vui lòng thử lại');
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

            return $this->respondServerError('Có lỗi xảy ra, vui lòng thử lại');
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

            return $this->respondServerError('Có lỗi xảy ra, vui lòng thử lại');
        }
    }

    public function validateCourse(string $slug)
    {
        try {
            $course = Course::query()->where('slug', $slug)->first();

            if (!$course) {
                return $this->respondNotFound('Không tìm thấy khoá học');
            }

            $errors = CourseValidatorService::validateCourse($course);

            if (!empty($errors)) {
                return $this->respondOk('Tiêu chí để có thể duyệt khoá học', $errors);
            }

            return $this->respondOk('Khoá học đã đạt yêu cầu kiểm duyệt');
        } catch (\Exception $e) {
            $this->logError($e);

            return $this->respondServerError('Có lỗi xảy ra, vui lòng thử lại');
        }
    }

    public function checkCourseComplete(string $slug)
    {
        try {
            $course = Course::query()->where('slug', $slug)->first();

            if (!$course) {
                return $this->respondNotFound('Không tìm thấy khoá học');
            }

            if ($course->user_id !== Auth::id()) {
                return $this->respondForbidden('Không có quyền thực hiện thao tác');
            }

            $courseObjectives = $this->checkCourseObjectives($course);
            $courseOverView = $this->checkCourseOverView($course);
            $courseCurriculum = $this->checkCurriculum($course);

            return $this->respondOk('Kiểm tra hoàn thiện khoá học', [
                'course_overview' => $courseOverView,
                'course_objectives' => $courseObjectives,
                'course_curriculum' => $courseCurriculum,
                'course_status' => $course->status,
            ]);
        } catch (\Exception $e) {
            $this->logError($e);

            return $this->respondServerError('Có lỗi xảy ra, vui lòng thử lại');
        }
    }

    private function checkCourseOverView(Course $course)
    {
        $data = [
            'name' => $course->name,
            'description' => $course->description,
            'thumbnail' => $course->thumbnail,
            'level' => $course->level,
            'category_id' => $course->category_id,
            'price' => $course->price,
        ];

        foreach ($data as $key => $value) {
            if (empty($value)) {
                return false;
            }
        }

        return true;
    }

    private function checkCourseObjectives(Course $course)
    {
        $benefits = is_string($course->benefits) ? json_decode($course->benefits, true) : $course->benefits;
        $requirements = is_string($course->requirements) ? json_decode($course->requirements, true) : $course->requirements;
        $qa = is_string($course->qa) ? json_decode($course->qa, true) : $course->qa;

        $benefits = is_array($benefits) ? $benefits : [];
        $requirements = is_array($requirements) ? $requirements : [];
        $qa = is_array($qa) ? $qa : [];

        return count($benefits) >= 4 && count($benefits) <= 10
            && count($requirements) >= 4 && count($requirements) <= 10
            && count($qa) >= 1 && count($qa) <= 5;
    }

    private function checkCurriculum(Course $course)
    {
        $chapters = $course->chapters()->get();

        return $chapters->count() >= 5;
    }
}
