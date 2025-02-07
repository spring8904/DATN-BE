@extends('layouts.app')
@section('title', $title)
@push('page-css')
    <link href="{{ asset('assets/css/custom.css') }}" rel="stylesheet" type="text/css" />
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
                        <p class="card-text fs-4">{{ $countWithdrawals->total_withdrawals ?? 0 }}</p>
                    </div>
                </div>
            </div>
            <div class="col-12 col-sm-6 col-md-3">
                <div class="card text-center h-75">
                    <div class="card-body">
                        <h5 class="card-title">Yêu cầu rút tiền đang chờ duyệt</h5>
                        <p class="card-text fs-4 text-success">{{ $countWithdrawals->completed_withdrawals ?? 0 }}</p>
                    </div>
                </div>
            </div>
            <div class="col-12 col-sm-6 col-md-3">
                <div class="card text-center h-75">
                    <div class="card-body">
                        <h5 class="card-title">Yêu cầu rút tiền thành công</h5>
                        <p class="card-text fs-4 text-warning">{{ $countWithdrawals->pending_withdrawals ?? 0 }}</p>
                    </div>
                </div>
            </div>
            <div class="col-12 col-sm-6 col-md-3">
                <div class="card text-center h-75">
                    <div class="card-body">
                        <h5 class="card-title">Yêu cầu rút tiền thất bại</h5>
                        <p class="card-text fs-4 text-danger">{{ $countWithdrawals->failed_withdrawals ?? 0 }}</p>
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
                        <div class="d-flex gap-2">
                            <div class="col-sm">
                                <div class="d-flex justify-content-sm-end">
                                    <div class="search-box ms-2">
                                        <input type="text" name="search_full" class="form-control search h-75"
                                            placeholder="Tìm kiếm..." data-search>
                                        <button id="search-full" class="h-75 ri-search-line search-icon m-0 p-0 border-0"
                                            style="background: none;"></button>
                                    </div>
                                </div>
                            </div>
                            <a href="{{ route('admin.withdrawals.export') }}" class="btn btn-sm btn-success h-75">Export dữ
                                liệu</a>
                            <button class="btn btn-sm btn-primary h-75" id="toggleAdvancedSearch">
                                Tìm kiếm nâng cao
                            </button>
                            <div class="dropdown">
                                <button class="btn btn-sm btn-primary h-75" type="button" id="filterDropdown"
                                    data-bs-toggle="dropdown" aria-expanded="false">
                                    <i class="ri-filter-2-line"></i>
                                </button>
                                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="filterDropdown"
                                    style="min-width: 500px;">
                                    <form>
                                        <div class="container">
                                            <li>
                                                <label for="amountRange" class="form-label">Số tiền</label>

                                                <div class="d-flex justify-content-between">
                                                    <span id="amountMin">10,000 VND</span>
                                                    <span id="amountMax">99,999,999 VND</span>
                                                </div>

                                                <div class="d-flex justify-content-between">
                                                    <input type="range" class="form-range w-50" id="amountMinRange"
                                                        name="amount_min" min="10000" max="49990000" step="10000"
                                                        value="10000" oninput="updateRange()" data-filter>
                                                    <input type="range" class="form-range w-50" id="amountMaxRange"
                                                        name="amount_max" min="50000000" max="99990000" step="10000"
                                                        value="99990000" oninput="updateRange()" data-filter>
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
                                            <li class="mt-2 d-flex gap-1">
                                                <button class="btn btn-sm btn-success flex-grow-1" type="reset" id="resetFilter">Reset</button>
                                                <button class="btn btn-sm btn-primary flex-grow-1" id="applyFilter">Áp
                                                    dụng</button>
                                            </li>
                                        </div>
                                    </form>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <!-- Tìm kiếm nâng cao -->
                    <div id="advancedSearch" class="card-header" style="display:none;">
                        <form>
                            <div class="row">
                                <div class="col-md-3">
                                    <label class="form-label">Tên chủ tài khoản</label>
                                    <input class="form-control form-control-sm" name="account_holder" type="text"
                                        placeholder="Nhập tên chủ tài khoản..."
                                        value="{{ request()->input('account_holder') ?? '' }}" data-advanced-filter>
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label">Số tài khoản</label>
                                    <input class="form-control form-control-sm" name="account_number" type="text"
                                        placeholder="Nhập tên số tài khoản..."
                                        value="{{ request()->input('account_number') ?? '' }}" data-advanced-filter>
                                </div>
                                <div class="col-md-3">
                                    <label for="statusItem" class="form-label">Trạng thái</label>
                                    <select class="form-select form-select-sm" name="status" id="statusItem"
                                        data-advanced-filter>
                                        <option value="">Tất cả trạng thái</option>
                                        <option value="completed" @selected(request()->input('status') === 'completed')>Hoàn thành</option>
                                        <option value="pending" @selected(request()->input('status') === 'pending')>Đang xử lý</option>
                                        <option value="failed" @selected(request()->input('status') === 'failed')>Thất bại</option>
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <label for="bankName" class="form-label">Ngân hàng</label>
                                    <select class="form-select form-select-sm" name="bank_name" id="bankName"
                                        data-advanced-filter>
                                        <option value="">Chọn ngân hàng</option>
                                        @foreach ($supportedBank as $bank)
                                            <option value="{{ $bank->name }}" @selected(request()->input('bank_name') === $bank->name)>
                                                {{ $bank->name . ' (' . $bank->short_name . ')' }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="mt-3 text-end">
                                    <button class="btn btn-sm btn-success" type="reset" id="resetFilter">Reset</button>
                                    <button class="btn btn-sm btn-primary" id="applyAdvancedFilter">Áp dụng</button>
                                </div>
                            </div>
                        </form>
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
                                                <td><span class="text-danger">{{ $withdrawal->account_number }}</span>
                                                </td>
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
                                                            Đang xử lý
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

        function updateRange() {
            var minValue = $('#amountMinRange').val();
            var maxValue = $('#amountMaxRange').val();
            document.getElementById('amountMin').textContent = formatCurrency(minValue) + ' VND';
            document.getElementById('amountMax').textContent = formatCurrency(maxValue) + ' VND';
        }

        function formatCurrency(value) {
            return value.replace(/\B(?=(\d{3})+(?!\d))/g, '.');
        }
        updateRange();

        $(document).on('click', '#resetFilter', function() {
            $('#amountMinRange').val(0);
            $('#amountMaxRange').val(99990000);
            updateRange();
            handleSearchFilter('');
        });
    </script>
    <script src="{{ asset('assets/js/custom/custom.js') }}"></script>
    <script src="{{ asset('assets/js/common/filter.js') }}"></script>
    <script src="{{ asset('assets/js/common/search.js') }}"></script>
    <script src="{{ asset('assets/js/common/handle-ajax-search&filter.js') }}"></script>
@endpush
