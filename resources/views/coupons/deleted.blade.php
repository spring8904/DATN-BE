@extends('layouts.app')


@section('content')
    <div class="container-fluid">

        <!-- start page title -->
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                    <h4 class="mb-sm-0">Quản lí coupons đã xóa</h4>

                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                            <li class="breadcrumb-item active">Danh sách coupon đã xóa</li>
                        </ol>
                    </div>

                </div>
            </div>
        </div>
        <!-- end page title -->

        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h4 class="card-title mb-0">Quản lí mã giảm giá đã xóa</h4>
                        <div class="d-flex gap-2">
                            <button class="btn btn-sm btn-primary" id="toggleAdvancedSearch">
                                Tìm kiếm nâng cao
                            </button>
                            <div class="dropdown">
                                <button class="btn btn-sm btn-primary" type="button" id="filterDropdown"
                                    data-bs-toggle="dropdown" aria-expanded="false">
                                    <i class="ri-filter-2-line"></i>
                                </button>
                                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="filterDropdown"
                                    style="min-width: 500px;">
                                    <div class="container">
                                        <div class="container">
                                            <div class="row">
                                                <li>
                                                    <label for="amountRange" class="form-label">Số lượt sử dụng</label>

                                                    <div class="d-flex justify-content-between">
                                                        <span id="amountMin">0</span>
                                                        <span id="amountMax">1000</span>
                                                    </div>

                                                    <div class="d-flex justify-content-between">
                                                        <input type="range" class="form-range w-100" id="amountMinRange"
                                                            name="used_count" min="0" max="1000" step="10"
                                                            value="0" oninput="updateRange()" data-filter>

                                                    </div>
                                                </li>
                                                <li class="col-6">
                                                    <div class="mb-2">
                                                        <label for="start_deleted" class="form-label">Ngày bắt đầu</label>
                                                        <input type="date" class="form-control form-control-sm"
                                                            name="start_deleted" id="start_deleted" data-filter
                                                            value="{{ request()->input('start_deleted') ?? '' }}">
                                                    </div>
                                                </li>
                                                <li class="col-6">
                                                    <div class="mb-2">
                                                        <label for="end_deleted" class="form-label">Ngày kết thúc</label>
                                                        <input type="date" class="form-control form-control-sm"
                                                            name="end_deleted" id="end_deleted" data-filter
                                                            value="{{ request()->input('end_deleted') ?? '' }}">
                                                    </div>
                                                </li>
                                            </div>
                                            <li class="mt-2">
                                                <button class="btn btn-sm btn-primary w-100" id="applyFilter">Áp
                                                    dụng</button>
                                            </li>

                                        </div>
                                    </div>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <!-- end card header -->

                    <!-- Tìm kiếm nâng cao -->
                    <div id="advancedSearch" class="card-header" style="display:none;">
                        <form id="filterForm">
                            <div class="row">
                                <div class="col-md-3">
                                    <label class="form-label">Mã giảm giá</label>
                                    <input class="form-control form-control-sm" name="code" type="text"
                                        value="{{ request()->input('code') ?? '' }}" placeholder="Nhập mã giảm giá..."
                                        data-advanced-filter>
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label">Tên mã giảm giá</label>
                                    <input class="form-control form-control-sm" name="name" type="text"
                                        value="{{ request()->input('name') ?? '' }}" placeholder="Nhập tên mã giảm giá..."
                                        data-advanced-filter>
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label">Người tạo</label>
                                    <input class="form-control form-control-sm" name="user_id" type="text"
                                        value="{{ request()->input('user_id') ?? '' }}" placeholder="Nhập người tạo..."
                                        data-advanced-filter>
                                </div>
                                <div class="col-md-3">
                                    <label for="statusItem" class="form-label">Loại giảm giá</label>
                                    <select class="form-select form-select-sm" name="discount_type" id="statusItem"
                                        data-advanced-filter>
                                        <option value="">Tất cả loại giảm giá</option>
                                        <option @selected(request()->input('discount_type') === 'percentage') value="percentage">Phần trăm</option>
                                        <option @selected(request()->input('discount_type') === 'fixed') value="fixed">Giảm trực tiếp</option>
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <label for="statusItem" class="form-label">Trạng thái</label>
                                    <select class="form-select form-select-sm" name="status" id="statusItem"
                                        data-advanced-filter>
                                        <option value="">Tất cả trạng thái</option>
                                        <option @selected(request()->input('status') === '1') value="1">Hoạt động</option>
                                        <option @selected(request()->input('status') === '0') value="0">Không hoạt động</option>
                                    </select>
                                </div>
                                <div class="mt-3 text-end">
                                    <button class="btn btn-sm btn-success" type="reset">Reset</button>
                                    <button class="btn btn-sm btn-primary" id="applyAdvancedFilter">Áp dụng</button>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="card-body" id="item_List">
                        <div class="listjs-table">
                            <div class="row g-4 mb-3">
                                <div class="col-sm-auto">
                                    <div>
                                        <button class="btn btn-danger" id="restoreSelected">
                                            <i class=" ri-restart-line"> Khôi phục</i>
                                        </button>
                                        <button class="btn btn-danger" id="deleteSelected">
                                            <i class="ri-delete-bin-2-line"> Xóa nhiều</i>
                                        </button>
                                    </div>
                                </div>
                                <div class="col-sm">
                                    <div class="d-flex justify-content-sm-end">
                                        <div class="search-box ms-2">
                                            <form action="{{ route('admin.coupons.deleted') }}" method="get">
                                                <input type="text" name="query" class="form-control search"
                                                    placeholder="Search..." value="{{ old('query') }}">
                                                <i class="ri-search-line search-icon"></i>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="table-responsive table-card mt-3 mb-1">
                                <table class="table align-middle table-nowrap" id="itemList">
                                    <thead class="table-light">
                                        <tr>
                                            <th scope="col" style="width: 50px;">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" id="checkAll">
                                                </div>
                                            </th>
                                            <th>ID</th>
                                            <th>Người tạo</th>
                                            <th>Tên mã giảm giá</th>
                                            <th>Mã giảm giá</th>
                                            <th>Giảm giá</th>
                                            <th>Trạng Thái</th>
                                            <th>Số lượng sử dụng</th>
                                            <th>Thời gian xóa</th>
                                        </tr>
                                    </thead>
                                    <tbody class="list form-check-all">
                                        @foreach ($coupons as $coupon)
                                            <tr>
                                                <th scope="row">
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="checkbox" name="itemID"
                                                            value="{{ $coupon->id }}">
                                                    </div>
                                                </th>

                                                <td class="id">{{ $coupon->id }}</td>
                                                <td class="id">{{ $coupon->user_id }}</td>
                                                <td class="customer_name">{{ $coupon->name }}</td>
                                                <td class="date">{{ $coupon->code }}</td>
                                                <td class="date">{{ $coupon->discount_value }}
                                                    ({{ $coupon->discount_type }})
                                                </td>
                                                @if ($coupon->status)
                                                    <td class="status"><span
                                                            class="badge bg-success-subtle text-success text-uppercase">
                                                            Active
                                                        </span></td>
                                                @else
                                                    <td class="status"><span
                                                            class="badge bg-danger-subtle text-danger text-uppercase">
                                                            InActive
                                                        </span></td>
                                                @endif
                                                <td class="date">{{ $coupon->used_count }}</td>
                                                <td class="date">{{ $coupon->deleted_at }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                                <div class="noresult" style="display: none">
                                    <div class="text-center">
                                        <lord-icon src="https://cdn.lordicon.com/msoeawqm.json" trigger="loop"
                                            colors="primary:#121331,secondary:#08a88a"
                                            style="width:75px;height:75px"></lord-icon>
                                        <h5 class="mt-2">Sorry! No Result Found</h5>
                                        <p class="text-muted mb-0">We've searched more than 150+ Orders We did not find
                                            any
                                            orders for you search.</p>
                                    </div>
                                </div>
                            </div>

                            {{ $coupons->appends(request()->query())->links() }}


                        </div>
                    </div><!-- end card -->
                </div>
                <!-- end col -->
            </div>
            <!-- end col -->
        </div>
        <!-- end row -->
    </div>
@endsection

@push('page-scripts')
    <script>
        var routeUrlFilter = "{{ route('admin.coupons.deleted') }}";
        var routeDeleteAll = "{{ route('admin.coupons.forceDelete', ':itemID') }}";
        var routeRestoreUrl = "{{ route('admin.coupons.restoreDelete', ':itemID') }}";
    </script>
    <script src="{{ asset('assets/js/custom/custom.js') }}"></script>
    <script src="{{ asset('assets/js/common/checkall-option.js') }}"></script>
    <script src="{{ asset('assets/js/common/delete-all-selected.js') }}"></script>
    <script src="{{ asset('assets/js/common/restore-all-selected.js') }}"></script>
    <script src="{{ asset('assets/js/common/filter.js') }}"></script>
    <script src="{{ asset('assets/js/common/search.js') }}"></script>
    <script src="{{ asset('assets/js/common/handle-ajax-search&filter.js') }}"></script>
@endpush
