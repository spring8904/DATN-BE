<div class="listjs-table" id="customerList">
    <div class="table-responsive table-card mt-3 mb-1">
        <table class="table align-middle table-nowrap" id="customerTable">
            <thead class="table-light">
                <tr>
                    <th>STT</th>
                    <th>Người mua</th>
                    <th>Mã khóa học</th>
                    <th>Tên khóa học</th>
                    <th>Tổng thanh toán</th>
                    <th>Trạng thái</th>
                    <th>Ngày mua</th>
                    <th>Ngày xác nhận mua</th>
                </tr>
            </thead>
            <tbody class="list">
                @foreach ($invoices as $invoice)
                    <tr>
                        <td>{{ $loop->index + 1 }}</td>
                        <td><span class="text-danger fw-bold">{{ $invoice->user->name }}</span>
                        </td>
                        <td>{{ $invoice->course->code }}</td>
                        <td>{{ Str::limit($invoice->course->name, 40) }}</td>
                        <td>{{ number_format($invoice->final_total) }} VND</td>
                        <td>
                            <span class="badge bg-primary">Hoàn thành</span>
                        </td>
                        <td>{{ optional(\Carbon\Carbon::parse($invoice->created_at))->format('d/m/Y') ?? 'NULL' }}
                        </td>
                        <td>{{ optional(\Carbon\Carbon::parse($invoice->updated_at))->format('d/m/Y') ?? 'NULL' }}
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="row justify-content-end">
        {{ $invoices->appends(request()->query())->links() }}
    </div>
</div>
