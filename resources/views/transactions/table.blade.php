<div class="listjs-table" id="customerList">
    <div class="table-responsive table-card mt-3 mb-1">
        <table class="table align-middle table-nowrap" id="customerTable">
            <thead class="table-light">
                <tr>
                    <th>STT</th>
                    <th>Người thực hiện giao dịch</th>
                    <th>Số tiền</th>
                    <th>Loại giao dịch</th>
                    <th>Trạng thái</th>
                    <th>Ngày tạo giao dịch</th>
                    <th>Ngày cập nhật giao dịch</th>
                </tr>
            </thead>
            <tbody class="list">
                @foreach ($transactions as $transaction)
                    <tr>
                        <td>{{ $loop->index + 1 }}</td>
                        <td><span class="text-primary fw-bold">{{ $transaction->user->name }}</span>
                        </td>
                        <td>{{ number_format($transaction->amount) }} VND</td>
                        <td>
                            @if ($transaction->type === 'invoice')
                                <span class="badge bg-success w-50">
                                    Mua bán
                                </span>
                            @elseif($transaction->type === 'withdrawal')
                                <span class="badge bg-info w-50">
                                    Rút tiền
                                </span>
                            @endif
                        </td>
                        <td>
                            @if ($transaction->status === 'completed')
                                <span class="badge bg-success w-75">
                                    Hoàn thành
                                </span>
                            @elseif($transaction->status === 'pending')
                                <span class="badge bg-warning w-75">
                                    Đang xử lý
                                </span>
                            @else
                                <span class="badge bg-danger w-75">
                                    Thất bại
                                </span>
                            @endif
                        </td>
                        <td>{{ optional(\Carbon\Carbon::parse($transaction->created_at))->format('d/m/Y') ?? 'NULL' }}
                        </td>
                        <td>{{ optional(\Carbon\Carbon::parse($transaction->updated_at))->format('d/m/Y') ?? 'NULL' }}
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="row justify-content-end">
        {{ $transactions->appends(request()->query())->links() }}
    </div>
</div>
