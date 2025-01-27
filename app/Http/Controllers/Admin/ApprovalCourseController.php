<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Traits\LoggableTrait;
use Illuminate\Http\Request;

class ApprovalCourseController extends Controller
{
    use LoggableTrait;

    public function index(Request $request)
    {
        $title = 'Kiểm duyệt khoá học';
        $subTitle = 'Danh sách khoá học ';

        $courses = Course::query()
            ->with('approvables')
            ->where('status', '!=', 'draft')
            ->paginate(10);

        return view('approval.course.index', compact([
            'title',
            'subTitle',
            'courses',
        ]));
    }

    public function show(string $slug)
    {
        try {
            $course = Course::query()
                ->with([
                    'category',
                    'user',
                    'chapters.lessons',
                ])
                ->where('slug', $slug)
                ->first();

            dd($course);

            if (!$course) {
                return redirect()->route('admin.approval.course.index')->with('error', 'Khóa học không tồn tại');
            }

            $title = 'Kiểm duyệt khoá học';
            $subTitle = 'Thông tin khoá học: ' . $course->name;

            return view('approval.course.show', compact([
                'course',
            ]));
        } catch (\Exception $e) {
            $this->logError($e);

            return redirect()->route('admin.approval.course.index')->with('error', 'Có lỗi xảy ra, vui lòng thử lại sau');
        }
    }
}
