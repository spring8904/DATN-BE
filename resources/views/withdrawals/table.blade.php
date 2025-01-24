<div class="listjs-table" id="customerList">
    <div class="table-responsive table-card mt-3 mb-1">
        <table class="table align-middle table-nowrap" id="customerTable">
            <thead class="table-light">
                <tr>
                    <th>STT</th>
                    <th>Tên chủ tài khoản</th>
                    <th>Số tài khoản</th>
                    <th>Ngân hàng</th>
                    <th>Số tiền</th>
                    <th>Ghi chú</th>
                    <th>Trạng thái</th>
                    <th>Ngày yêu cầu</th>
                    <th>Ngày xác nhận</th>
                </tr>
            </thead>
            <tbody class="list">
                @foreach ($withdrawals as $withdrawal)
                <tr>
                    <td>{{ $loop->index + 1 }}</td>
                    <td>{{ $withdrawal->account_holder }}</td>
                    <td><span class="text-danger">{{ $withdrawal->account_number }}</span></td>
                    <td>{{ $withdrawal->bank_name }}</td>
                    <td>{{ number_format($withdrawal->amount) }} VND</td>
                    <td><textarea class="border-0 bg-white" disabled>{{ $withdrawal->note }}</textarea></td>
                    <td>
                        @if ($withdrawal->status === 'completed')
                        <span class="badge bg-success w-100">
                            Hoàn thành
                        </span>
                        @elseif($withdrawal->status === 'pending')
                        <span class="badge bg-warning w-100">
                            Chờ xử lý
                        </span>
                        @else
                        <span class="badge bg-danger w-100">
                            Thất bại
                        </span>
                        @endif
                    </td>
                    <td>{{ optional(\Carbon\Carbon::parse($withdrawal->request_date))->format('d/m/Y') ?? 'NULL' }}</td>
                    <td>{{ optional(\Carbon\Carbon::parse($withdrawal->completed_date))->format('d/m/Y') ?? 'NULL'}}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="row justify-content-end">
        {{ $withdrawals->appends(request()->query())->links() }}
    </div>
</div>