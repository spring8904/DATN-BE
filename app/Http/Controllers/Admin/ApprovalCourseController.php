<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Approvable;
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

        $approvals = Approvable::query()
            ->with([
                'approver',
                'course.user',
            ])
            ->where('approvable_type', Course::class)
            ->paginate(10);

        return view('approval.course.index', compact([
            'title',
            'subTitle',
            'approvals',
        ]));
    }

    public function show(string $id)
    {
        try {
            $approval = Approvable::query()
                ->with([
                    'approver',
                    'course.user',
                ])->findOrFail($id);

            $title = 'Kiểm duyệt khoá học';
            $subTitle = 'Thông tin khoá học: ' . $approval->course->name;

            return view('approval.course.show', compact([
                'title',
                'subTitle',
                'approval',
            ]));
        } catch (\Exception $e) {
            $this->logError($e);

            return redirect()->route('admin.approval.course.index')->with('error', 'Có lỗi xảy ra, vui lòng thử lại sau');
        }
    }
}
