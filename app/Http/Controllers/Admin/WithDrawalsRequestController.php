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
            $queryCountWithdrawals = WithdrawalRequest::query()->selectRaw(
                'count(id) as total_withdrawals,
                sum(status = "active") as completed_withdrawals,
                sum(status = "inactive") as _withdrawals,
                sum(status = "blocked") as completed_withdrawals'
            );

            if ($request->hasAny(['status', 'request_date', 'completed_date', 'bank_name', 'amount'])) $queryWithdrawals = $this->filter($request, $queryWithdrawals);
            if ($request->has('search_full')) $queryWithdrawals = $this->search($request, $queryWithdrawals);

            $withdrawals = $queryWithdrawals->paginate(10);

            if ($request->ajax() && $request->hasAny(['status', 'request_date', 'completed_date', 'bank_name', 'amount', 'search_full'])) {
                $html = view('withdrawals.table', compact('withdrawals'))->render();
                return response()->json(['html' => $html]);
            }

            return view('withdrawals.index', compact(['title', 'subTitle', 'withdrawals']));
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
            'amount' => ['queryWhere' => '<=']
        ];

        foreach ($filters as $filter => $value) {
            $filterValue = $request->input($filter);

            if (!empty($filterValue)) {
                $query->where($filter, $value['queryWhere'], $filterValue);
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
                    ->orWhere('account_holder', 'LIKE', "%$searchTerm%");
            });
        }

        return $query;
    }
}
