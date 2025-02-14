<?php

namespace App\Http\Controllers\API\Common;

use App\Models\Category;
use App\Models\Course;
use App\Traits\ApiResponseTrait;
use App\Traits\LoggableTrait;

class CourseController
{
    use LoggableTrait, ApiResponseTrait;

    public function getDiscountedCourses()
    {
        try {
            $courses = Course::query()
                ->with([
                    'category:id,name',
                    'user:id,name,avatar',
                    'chapters' => function ($query) {
                        $query->withCount('lessons');
                    },
                ])
                ->withCount([
                    'chapters',
                    'lessons'
                ])
                ->where('price_sale', '>', 0)
                ->where('visibility', '=', 'public')
                ->where('status', '=', 'approved')
                ->orderBy('total_student', 'desc')
                ->limit(10)
                ->get();;

            if ($courses->isEmpty()) {
                return $this->respondNotFound('Không có dữ liệu');
            }

            return $this->respondOk('Danh sách khoá học đang giảm giá', $courses);
        } catch (\Exception $e) {
            $this->logError($e);

            return $this->respondServerError('Internal Server Error');
        }
    }

    public function getFreeCourses()
    {
        try {
            $courses = Course::query()
                ->with([
                    'category:id,name',
                    'user:id,name,avatar',
                    'chapters' => function ($query) {
                        $query->withCount('lessons');
                    },
                ])
                ->withCount([
                    'chapters',
                    'lessons'
                ])
                ->where('is_free', '=', 1)
                ->where('visibility', '=', 'public')
                ->where('status', '=', 'approved')
                ->orderBy('total_student', 'desc')
                ->limit(10)
                ->get();;

            if ($courses->isEmpty()) {
                return $this->respondNotFound('Không có dữ liệu');
            }

            return $this->respondOk('Danh sách khoá học miễn phí', $courses);
        } catch (\Exception $e) {
            $this->logError($e);

            return $this->respondServerError('Internal Server Error');
        }
    }

    public function getPopularCourses()
    {
        try {
            $courses = Course::query()
                ->with([
                    'category:id,name',
                    'user:id,name,avatar',
                    'chapters' => function ($query) {
                        $query->withCount('lessons');
                    },
                ])
                ->withCount([
                    'chapters',
                    'lessons'
                ])
                ->where('is_popular', '=', 1)
                ->where('visibility', '=', 'public')
                ->where('status', '=', 'approved')
                ->orderBy('total_student', 'desc')
                ->limit(10)
                ->get();;

            if ($courses->isEmpty()) {
                return $this->respondNotFound('Không có dữ liệu');
            }

            return $this->respondOk('Danh sách khoá học nổi bật', $courses);

        } catch (\Exception $e) {
            $this->logError($e);

            return $this->respondServerError('Internal Server Error');
        }
    }

    public function getTopCategoriesWithMostCourses()
    {
        try {
            $categories = Category::query()
                ->with([
                    'courses' => function ($query) {
                        $query->where('visibility', '=', 'public')
                            ->where('status', '=', 'approved')
                            ->orderBy('total_student', 'desc')
                            ->limit(5);
                    },
                ])
                ->whereHas('courses', function ($query) {
                    $query->where('visibility', '=', 'public')
                        ->where('status', '=', 'approved');
                })
                ->withCount('courses')
                ->having('courses_count', '>=', 5)
                ->limit(5)
                ->get();

            if ($categories->isEmpty()) {
                return $this->respondNotFound('Không có dữ liệu');
            }

            return $this->respondOk('Danh sách danh mục', $categories);
        } catch (\Exception $e) {
            $this->logError($e);

            return $this->respondServerError('Internal Server Error');
        }
    }

    public function getCourseDetail(string $slug)
    {
        try {
            $course = Course::query()
                ->with([
                    'category:id,name,slug',
                    'user:id,name,avatar,created_at',
                    'chapters' => function ($query) {
                        $query->with([
                            'lessons',
                            'lessons.lessonable',
                        ])->withCount('lessons');
                    },
                ])
                ->withCount([
                    'chapters',
                    'lessons'
                ])
                ->where('slug', '=', $slug)
                ->where('visibility', '=', 'public')
                ->where('status', '=', 'approved')
                ->where('slug', '=', $slug)
                ->first();

            if (!$course) {
                return $this->respondNotFound('Không có dữ liệu');
            }

            return $this->respondOk('Chi tiết khoá học: ' . $course->name, $course);
        } catch (\Exception $e) {
            $this->logError($e);

            return $this->respondServerError('Internal Server Error');
        }
    }
}
