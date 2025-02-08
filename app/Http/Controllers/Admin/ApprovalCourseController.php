<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Approvable;
use App\Models\Course;
use App\Traits\LoggableTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ApprovalCourseController extends Controller
{
    use LoggableTrait;

    public function index(Request $request)
    {
        $title = 'Kiểm duyệt khoá học';
        $subTitle = 'Danh sách khoá học ';

        $queryApprovals = Approvable::query()
            ->with([
                'approver',
                'course',
                'user'
            ])
            ->where('approvable_type', Course::class);

        $approvalCount = Approvable::query()
            ->selectRaw('
                count(id) as total_approval,
                sum(status = "pending") as pending_approval,
                sum(status = "approved") as approved_approval,
                sum(status = "rejected") as rejected_approval
            ')->where('approvable_type', Course::class)->first();

        if ($request->hasAny([
            'amount_min',
            'amount_max',
            'request_start_date',
            'request_end_date',
            'approval_start_date',
            'approval_end_date',
            'course_name_approved',
            'user_name_approved',
            'approver_name_approved',
            'status'
        ])) {
            $queryApprovals = $this->filter($request, $queryApprovals);
        }

        if ($request->has('search_full'))
            $queryApprovals = $this->search($request, $queryApprovals);

        $approvals = $queryApprovals->paginate(10);

        if ($request->ajax()) {
            $html = view('approval.course.table', compact('approvals'))->render();
            return response()->json(['html' => $html]);
        }


        return view('approval.course.index', compact([
            'title',
            'subTitle',
            'approvals',
            'approvalCount'
        ]));
    }

    public function show(string $id)
    {
        try {
            $approval = Approvable::query()
                ->with([
                    'approver',
                    'course.user',
                ])
                ->latest('created_at')
                ->findOrFail($id);

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

    public function approve(Request $request, string $id)
    {
        return $this->updateApprovalStatus($id, 'approved', 'Khoá học đã được duyệt');
    }

    public function reject(Request $request, string $id)
    {
        $note = $request->note ?? 'Khoá học đã bị từ chối';
        return $this->updateApprovalStatus($id, 'rejected', $note);
    }

    private function updateApprovalStatus(string $id, string $status, string $note)
    {
        try {
            DB::beginTransaction();

            $approval = Approvable::query()->findOrFail($id);

            $approval->status = $status;
            $approval->note = $note;
            $approval->{$status . '_at'} = now();
            $approval->approver_id = auth()->id();
            $approval->save();

            $approval->course->update(['status' => $status]);

            DB::commit();

            return redirect()->back()->with('success', "Khoá học đã được $status");
        } catch (\Exception $e) {
            DB::rollBack();

            $this->logError($e);

            return redirect()->route('admin.approvals.courses.index')
                ->with('error', 'Có lỗi xảy ra, vui lòng thử lại sau');
        }
    }

    private function filter($request, $query)
    {
        $filters = [
            'status' => ['queryWhere' => '='],
            'course_name_approved' => null,
            'user_name_approved' => null,
            'approver_name_approved' => null,
            'course_price_approved' => ['attribute' => ['amount_min' => '>=', 'amount_max' => '<=']],
            'request_date' => ['attribute' => ['request_start_date' => '>=', 'request_end_date' => '<=']],
            'approval_date' => ['filed' => ['approved_at', 'rejected_at'], 'attribute' => ['approval_start_date' => '>=', 'approval_end_date' => '<=']],
        ];


        foreach ($filters as $filter => $value) {
            $filterValue = $request->input($filter);
            $elementFilter = explode('_', $filter);

            if (str_contains($filter, '_') && count($elementFilter) === 3) {
                $elementFilter = explode('_', $filter);
                $relation = $elementFilter[0];
                $field = $elementFilter[1];

                if (method_exists($query->getModel(), $relation)) {
                    if (!empty($value) && is_array($value) && !empty($value['attribute'])) {
                        $hasValidFilter = false;

                        foreach ($value['attribute'] as $keyAttribute => $valueAttribute) {
                            $filterValue = $request->input($keyAttribute);
                            if (!empty($filterValue)) {
                                $hasValidFilter = true;
                                break;
                            }
                        }
                        if ($hasValidFilter) {
                            $query->whereHas($relation, function ($query) use ($field, $value, $request) {
                                foreach ($value['attribute'] as $keyAttribute => $valueAttribute) {
                                    $filterValue = $request->input($keyAttribute);
                                    if (!empty($filterValue)) {
                                        $query->where($field, $valueAttribute, $filterValue);
                                    }
                                }
                            });
                        }
                    } else {
                        if (!empty($filterValue)) {
                            $query->whereHas($relation, function ($query) use ($field, $filterValue) {
                                $query->where($field, 'LIKE', "%$filterValue%");
                            });
                        }
                    }
                }
            } else {
                if (!empty($filterValue)) {
                    $operator = isset($value['queryWhere']) ? $value['queryWhere'] : '=';
                    $filterValue = ($operator === 'LIKE') ? "%$filterValue%" : $filterValue;
                    $query->where($filter, $operator, $filterValue);
                } else {
                    if (!empty($value['attribute']) && is_array($value['attribute'])) {
                        if (isset($value['filed']) && is_array($value['filed']) && sizeof($value['filed']) >= 1) {
                            $query->where(function ($query) use ($request, $value) {
                                foreach ($value['filed'] as $filed) {
                                    $query->orWhere(function ($query) use ($filed, $request, $value) {
                                        foreach ($value['attribute'] as $keyAttribute => $valueAttribute) {
                                            $filterValue = $request->input($keyAttribute);
                                            if (!empty($filterValue)) {
                                                $query->where($filed, $valueAttribute, $filterValue);
                                            }
                                        }
                                    });
                                }
                            });
                        } else {
                            foreach ($value['attribute'] as $keyAttribute => $valueAttribute) {
                                $filterValue = $request->input($keyAttribute);
                                if (!empty($filterValue)) {
                                    $query->where(function ($query) use ($filter, $filterValue, $valueAttribute) {
                                        $query->where($filter, $valueAttribute, $filterValue);
                                    });
                                }
                            }
                        }
                    }
                }
            }
        }


        return $query;
    }

    private function search($request, $query)
    {
        if (!empty($request->search_full)) {
            $searchTerm = $request->search_full;
            $query->where(function ($query) use ($searchTerm) {
                $query->where('note', 'LIKE', "%$searchTerm%")
                    ->orWhereHas('approver', function ($query) use ($searchTerm) {
                        $query->where('name', 'LIKE', "%$searchTerm%");
                    })
                    ->orWhereHas('user', function ($query) use ($searchTerm) {
                        $query->where('name', 'LIKE', "%$searchTerm%");
                    })
                    ->orWhereHas('course', function ($query) use ($searchTerm) {
                        $query->where('name', 'LIKE', "%$searchTerm%");
                    });
            });
        }

        return $query;
    }
}
