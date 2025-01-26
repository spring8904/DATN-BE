<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\WithdrawalRequest;
use App\Traits\LoggableTrait;
use Illuminate\Http\Request;

class WithDrawalsRequestController extends Controller
{
    use LoggableTrait;
    public function index(Request $request)
    {
        try {
            $title = 'Quản lý thanh toán';
            $subTitle = 'Yêu cầu rút tiền';

            $queryWithdrawals = WithdrawalRequest::query()->latest('id');
            $countWithdrawals = WithdrawalRequest::query()->selectRaw(
    'count(id) as total_withdrawals,
                sum(status = "completed") as completed_withdrawals,
                sum(status = "pending") as pending_withdrawals,
                sum(status = "failed") as failed_withdrawals'
            )->first();

            if ($request->hasAny(['status', 'request_date', 'completed_date', 'bank_name', 'amount_min', 'amount_max', 'account_number', 'account_holder']))
                $queryWithdrawals = $this->filter($request, $queryWithdrawals);

            if ($request->has('search_full'))
                $queryWithdrawals = $this->search($request, $queryWithdrawals);

            $withdrawals = $queryWithdrawals->paginate(10);

            if ($request->ajax()) {
                $html = view('withdrawals.table', compact('withdrawals'))->render();
                return response()->json(['html' => $html]);
            }

            return view('withdrawals.index', compact(['title', 'subTitle', 'withdrawals', 'countWithdrawals']));
        } catch (\Exception $e) {

            $this->logError($e);

            return redirect()->back()->with('error', 'Có lỗi xảy ra, vui lòng thử lại sau');
        }
    }

    private function filter($request, $query)
    {
        $filters = [
            'status' => ['queryWhere' => '='],
            'request_date' => ['queryWhere' => '>='],
            'completed_date' => ['queryWhere' => '<='],
            'bank_name' => ['queryWhere' => '='],
            'amount' => ['queryWhere' => 'BETWEEN', 'attribute' => ['amount_min', 'amount_max']],
            'account_holder' => ['queryWhere' => 'LIKE'],
            'account_number' => ['queryWhere' => 'LIKE'],
        ];


        foreach ($filters as $filter => $value) {
            if (!empty($value['queryWhere'])) {
                if ($value['queryWhere'] !== 'BETWEEN') {
                    $filterValue = $request->input($filter);
                    if (!empty($filterValue)) {
                        $filterValue = $value['queryWhere'] === 'LIKE' ? "%$filterValue%" : $filterValue;
                        $query->where($filter, $value['queryWhere'], $filterValue);
                    }
                } else {
                    $filterValueBetweenA = $request->input($value['attribute'][0]);
                    $filterValueBetweenB = $request->input($value['attribute'][1]);

                    if (!empty($filterValueBetweenA) && !empty($filterValueBetweenB)) {
                        $query->whereBetween($filter, [$filterValueBetweenA, $filterValueBetweenB]);
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
                $query->where('account_number', 'LIKE', "%$searchTerm%")
                    ->orWhere('account_holder', 'LIKE', "%$searchTerm%")
                    ->orwhere('note', 'LIKE', "%$searchTerm%");
            });
        }

        return $query;
    }
}
