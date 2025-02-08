<div class="listjs-table" id="customerList">
    <div class="table-responsive table-card mt-3 mb-1">
        <table class="table align-middle table-nowrap" id="customerTable">
            <thead class="table-light">
                <tr>
                    <th>STT</th>
                    <th>Mã giao dịch</th>
                    <th>Người thực hiện giao dịch</th>
                    <th>Email</th>
                    <th>Số tiền</th>
                    <th>Loại giao dịch</th>
                    <th>Trạng thái</th>
                    <th>Ngày tạo giao dịch</th>
                    <th>Thao tác</th>
                </tr>
            </thead>
            <tbody class="list">
                @foreach ($transactions as $transaction)
                    <tr>
                        <td>{{ $loop->index + 1 }}</td>
                        <td>{{ $transaction->transaction_code ?? '' }}</td>
                        <td><span
                                class="text-primary fw-bold">{{ $transaction->invoice->user->name ?? '' }}</span>
                        </td>
                        <td>{{ $transaction->invoice->user->email ?? '' }}</td>
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
                            @if ($transaction->status === 'Giao dịch thành công')
                                <span class="badge bg-success w-75">
                                    Hoàn thành
                                </span>
                            @elseif($transaction->status === 'Chờ xử lý')
                                <span class="badge bg-warning w-75">
                                    Đang xử lý
                                </span>
                            @else
                                <span class="badge bg-danger w-75">
                                    Thất bại
                                </span>
                            @endif
                        </td>
                        <td>{{ $transaction->created_at }}
                        </td>
                        <td>
                            <a
                                href="{{ route('admin.transactions.show', $transaction->transaction_code) }}">
                                <button class="btn btn-sm btn-info edit-item-btn">
                                    <span class="ri-eye-line"></span>
                                </button>
                            </a>
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