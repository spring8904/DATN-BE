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

            $approvals = Approvable::query()
                ->with([
                    'approver',
                    'user',
                ])
                ->where('approvable_type', User::class)
                ->paginate(10);

            return view('approval.instructor.index', compact([
                'title',
                'subTitle',
                'approvals',
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
}
