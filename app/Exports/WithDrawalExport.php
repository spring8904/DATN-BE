<?php

namespace App\Exports;

use App\Models\WithdrawalRequest;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class WithDrawalExport implements FromCollection, WithHeadings, WithMapping
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return WithdrawalRequest::all();
    }

    public function headings(): array {
        return [
            'STT',
            'Tên chủ tài khoản',
            'Số tài khoản',
            'Ngân hàng',
            'Số tiền',
            'Ghi chú',
            'Trạng thái giao dịch',
            'Ngày yêu cầu',
            'Ngày hoàn thành'
        ];
    }

    public function map($withdrawal): array {
        static $index = 1;

        return [
            $index++,
            $withdrawal->account_holder,
            $withdrawal->account_number,
            $withdrawal->bank_name,
            $withdrawal->amount,
            $withdrawal->note,
            $withdrawal->status,
            $withdrawal->request_date,
            $withdrawal->completed_date
        ];
    }
}
