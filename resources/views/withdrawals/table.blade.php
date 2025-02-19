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
                <th>Trạng thái</th>
                <th>Ngày yêu cầu</th>
                <th>Ngày xác nhận</th>
                <th>Thao tác</th>
            </tr>
            </thead>
            <tbody class="list">
            @foreach ($withdrawals as $withdrawal)
                <tr>
                    <td>{{ $loop->index + 1 }}</td>
                    <td>{{ $withdrawal->account_holder ?? 'Không có thông tin' }}</td>
                    <td><span class="text-danger">{{ $withdrawal->account_number ?? 'Không có thông tin' }}</span>
                    </td>
                    <td>{{ \Illuminate\Support\Str::limit($withdrawal->bank_name ?? 'Không có thông tin',40) }}</td>
                    <td>{{ number_format($withdrawal->amount) }} VND</td>
                    <td>
                        @if ($withdrawal->status === 'completed')
                            <span class="badge bg-success w-100">
                                    Hoàn thành
                                </span>
                        @elseif($withdrawal->status === 'pending')
                            <span class="badge bg-warning w-100">
                                    Đang xử lý
                                </span>
                        @else
                            <span class="badge bg-danger w-100">
                                    Thất bại
                                </span>
                        @endif
                    </td>
                    <td>{!! $withdrawal->request_date ? \Carbon\Carbon::parse($withdrawal->request_date)->format('d/m/Y') : '<span class="btn btn-sm btn-soft-warning">Không có thông tin</span>' !!}
                    </td>
                    <td>{!! $withdrawal->completed_date ? \Carbon\Carbon::parse($withdrawal->completed_date)->format('d/m/Y') : '<span class="btn btn-sm btn-soft-warning">Chưa xác nhận</span>' !!}
                    </td>
                    <td>
                        <a href="{{ route('admin.approvals.courses.show', $withdrawal->id) }}">
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
        {{ $withdrawals->appends(request()->query())->links() }}
    </div>
</div>