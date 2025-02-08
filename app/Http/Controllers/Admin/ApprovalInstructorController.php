<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Approvable;
use App\Models\User;
use App\Traits\LoggableTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ApprovalInstructorController extends Controller
{
    use LoggableTrait;

    public function index(Request $request)
    {
        try {
            $title = 'Kiểm duyệt giảng viên';
            $subTitle = 'Danh sách giảng viên ';

            $queryApprovals = Approvable::query()
                ->with([
                    'approver',
                    'user',
                ])
                ->where('approvable_type', User::class);

            $approvalCount = Approvable::query()
                ->selectRaw('
                    count(id) as total_approval,
                    sum(status = "pending") as pending_approval,
                    sum(status = "approved") as approved_approval,
                    sum(status = "rejected") as rejected_approval
                ')->where('approvable_type', User::class)->first();

            if ($request->hasAny([
                'request_start_date',
                'request_end_date',
                'approval_start_date',
                'approval_end_date',
                'user_email_approved',
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
                $html = view('approval.instructor.table', compact('approvals'))->render();
                return response()->json(['html' => $html]);
            }

            return view('approval.instructor.index', compact([
                'title',
                'subTitle',
                'approvals',
                'approvalCount'
            ]));
        } catch (\Exception $e) {
            $this->logError($e);

            return redirect()->route('admin.approvals.instructor.index')
                ->with('error', 'Có lỗi xảy ra, vui lòng thử lại sau');
        }
    }

    public function show(string $id)
    {
        try {
            $approval = Approvable::query()
                ->with([
                    'approver',
                    'user.profile.careers',
                ])
                ->findOrFail($id);

            $score = $this->calculateCompletenessScore($approval->user);

            $title = 'Kiểm duyệt giảng viên';
            $subTitle = 'Thông tin giảng viên: ' . $approval->user->name;

            return view('approval.instructor.show', compact([
                'title',
                'subTitle',
                'approval',
                'score',
            ]));
        } catch (\Exception $e) {
            $this->logError($e);

            return redirect()->route('admin.approvals.instructors.index')
                ->with('error', 'Có lỗi xảy ra, vui lòng thử lại sau');
        }
    }

    private function calculateCompletenessScore($user)
    {
        $score = 0;
        $criteriaCount = 0;
        $personalInfoRequired = ['phone', 'address', 'bio', 'about_me'];
        $missingInfoCount = 0;

        if (empty($user->name) || empty($user->email) || empty($user->avatar)) {
            $missingInfoCount++;
        }

        foreach ($personalInfoRequired as $info) {
            if (empty($user->profile->$info)) {
                $missingInfoCount++;
            }
        }

        if ($missingInfoCount == 0) {
            $score += 60;
        } else {
            $score += 60 - (10 * $missingInfoCount);
        }
        $criteriaCount++;

        $qa_systems = json_decode($user->profile->qa_systems, true);
        if (count($qa_systems) > 0) {
            $score += 10;
            $criteriaCount++;
        }

        $qualifications = $user->profile->careers;
        if (count($qualifications) > 0) {
            $score += 10;
            $criteriaCount++;
        }

        $certificates = json_decode($user->profile->certificates, true);
        if (count($certificates) > 0) {
            $score += 10;
            $criteriaCount++;
        }

        if ($user->profile->experience >= 1) {
            $score += 5;
            $criteriaCount++;
        }

        if (!empty($user->profile->bio)) {
            $score += 5;
            $criteriaCount++;
        }

        if ($criteriaCount > 0) {
            $score = round($score, 2);
        }

        return $score;
    }

    public function approve(Request $request, string $id)
    {
        return $this->updateApprovalStatus($id, 'approved', 'Người hướng dẫn đã được kiểm duyệt');
    }

    public function reject(Request $request, string $id)
    {
        $note = $request->note ?? 'Người hướng dẫn đã bị từ chối';
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

            DB::commit();

            return redirect()->back()->with('success', "Người hướng dẫn đã được $status");
        } catch (\Exception $e) {
            DB::rollBack();
            $this->logError($e);

            return redirect()->route('admin.approvals.instructors.index')
                ->with('error', 'Có lỗi xảy ra, vui lòng thử lại sau');
        }
    }
    private function filter($request, $query)
    {
        $filters = [
            'status' => ['queryWhere' => '='],
            'user_email_approved' => null,
            'user_name_approved' => null,
            'approver_name_approved' => null,
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
                    if (!empty($filterValue)) {
                        $query->whereHas($relation, function ($query) use ($field, $filterValue) {
                            $query->where($field, 'LIKE', "%$filterValue%");
                        });
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
                        $query->where('name', 'LIKE', "%$searchTerm%")->orWhere('email', 'LIKE', "%$searchTerm%");
                    });
            });
        }

        return $query;
    }
}
