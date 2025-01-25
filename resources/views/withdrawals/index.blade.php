@extends('layouts.app')

@push('page-css')
@endpush

@section('content')
    <div class="container-fluid">

        <!-- start page title -->
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                    <h4 class="mb-sm-0">Yêu cầu rút tiền</h4>
                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                            <li class="breadcrumb-item active"><a
                                    href="{{ route('admin.withdrawals.index') }}">{{ $subTitle }}</a></li>
                        </ol>
                    </div>

                </div>
            </div>
        </div>
        <!-- end page title -->

        <!-- social-customer -->
        <div class="row mb-2">
            <div class="col-12 col-sm-6 col-md-3">
                <div class="card text-center h-75">
                    <div class="card-body">
                        <h5 class="card-title">Tổng số yêu cầu rút tiền</h5>
                        <p class="card-text fs-4">{{ $userCounts->total_users ?? 0 }}</p>
                    </div>
                </div>
            </div>
            <div class="col-12 col-sm-6 col-md-3">
                <div class="card text-center h-75">
                    <div class="card-body">
                        <h5 class="card-title">Yêu cầu rút tiền đang chờ duyệt</h5>
                        <p class="card-text fs-4 text-success"></p>
                    </div>
                </div>
            </div>
            <div class="col-12 col-sm-6 col-md-3">
                <div class="card text-center h-75">
                    <div class="card-body">
                        <h5 class="card-title">Yêu cầu rút tiền thành công</h5>
                        <p class="card-text fs-4 text-warning"></p>
                    </div>
                </div>
            </div>
            <div class="col-12 col-sm-6 col-md-3">
                <div class="card text-center h-75">
                    <div class="card-body">
                        <h5 class="card-title">Yêu cầu rút tiền thất bại</h5>
                        <p class="card-text fs-4 text-danger"></p>
                    </div>
                </div>
            </div>
        </div>
        <!-- End social-customer -->

        <!-- List-customer -->
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h4 class="card-title mb-0">Danh sách yêu cầu rút tiền</h4>
                        <div class="col-sm">
                            <div class="d-flex justify-content-sm-end">
                                <div class="search-box ms-2">
                                    <input type="text" name="search_full" class="form-control search"
                                        placeholder="Tìm kiếm..." data-search>
                                    <button id="search-full" class="ri-search-line search-icon m-0 p-0 border-0"
                                        style="background: none;"></button>
                                </div>
                            </div>
                        </div>
                        <div class="dropdown ms-3">
                            <button class="btn btn-sm btn-primary" type="button" id="filterDropdown"
                                data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="ri-filter-2-line"></i>
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="filterDropdown"
                                style="min-width: 500px;">
                                <div class="container">
                                    <div class="row">
                                        <li class="col-6">
                                            <label for="statusItem" class="form-label">Trạng thái</label>
                                            <select class="form-select form-select-sm mb-2" name="status" id="statusItem"
                                                data-filter>
                                                <option value="">Tất cả trạng thái</option>
                                                <option value="completed">Hoàn thành</option>
                                                <option value="pending">Chờ xử lý</option>
                                                <option value="failed">Thất bại</option>
                                            </select>
                                        </li>
                                        <li class="col-6">
                                            <label for="bankItem" class="form-label">Ngân hàng</label>
                                            <select class="form-select form-select-sm mb-2" name="bank_name" id="bankItem"
                                                data-filter>
                                                <option value="">Chọn ngân hàng</option>
                                                <option value="TPBank">TPBank</option>
                                                <option value="ABBANK">ABBANK</option>
                                                <option value="MB Bank">MB Bank</option>
                                            </select>
                                        </li>
                                    </div>
                                    <li>
                                        <label for="amountRange" class="form-label">Số tiền</label>
                                        <input type="range" class="form-range" name="amount" id="amountRange"
                                            min="10000" max="99999999" step="10000"
                                            value="{{ request()->input('amount') ?? 10000000 }}" data-filter>
                                        <div class="d-flex justify-content-between">
                                            <span id="amountMin">10,000 VND</span>
                                            <span id="amountMax">99,999,999 VND</span>
                                        </div>
                                    </li>
                                    <div class="row">
                                        <li class="col-6">
                                            <div class="mb-2">
                                                <label for="startDate" class="form-label">Ngày yêu cầu</label>
                                                <input type="date" class="form-control form-control-sm"
                                                    name="request_date" id="dateRequest" data-filter
                                                    value="{{ request()->input('request_date') ?? '' }}">
                                            </div>
                                        </li>
                                        <li class="col-6">
                                            <div class="mb-2">
                                                <label for="endDate" class="form-label">Ngày xác nhận</label>
                                                <input type="date" class="form-control form-control-sm"
                                                    name="completed_date" id="dateComplete" data-filter
                                                    value="{{ request()->input('completed_date') ?? '' }}">
                                            </div>
                                        </li>
                                    </div>
                                    <li class="mt-2">
                                        <button class="btn btn-sm btn-primary w-100" id="applyFilter">Áp dụng</button>
                                    </li>
                                </div>
                            </ul>
                        </div>
                    </div>
                    <!-- end card header -->
                    <div class="card-body" id="item_List">
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
                                                <td>
                                                    <textarea class="border-0 bg-white" disabled>{{ $withdrawal->note }}</textarea>
                                                </td>
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
                                                <td>{{ optional(\Carbon\Carbon::parse($withdrawal->request_date))->format('d/m/Y') ?? 'NULL' }}
                                                </td>
                                                <td>{{ optional(\Carbon\Carbon::parse($withdrawal->completed_date))->format('d/m/Y') ?? 'NULL' }}
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
                    </div>
                    <!-- end card -->
                </div>
            </div>
            <!-- end col -->
        </div>
        <!-- end List-customer -->
    </div>
@endsection

@push('page-scripts')
    <script>
        var routeUrlFilter = "{{ route('admin.withdrawals.index') }}";
    </script>
    <script src="{{ asset('assets/js/common/filter-search.js') }}"></script>
@endpush
