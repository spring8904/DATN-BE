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
                            <li class="breadcrumb-item active">Danh sách banner đã xóa</li>
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
                        <h4 class="card-title mb-0">Quản lí banner đã xóa</h4>
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
                                                        <label for="startDate" class="form-label">Ngày bắt đầu</label>
                                                        <input type="date" class="form-control form-control-sm"
                                                            name="start_date" id="startDate" data-filter
                                                            value="{{ request()->input('start_date') ?? '' }}">
                                                    </div>
                                                </li>
                                                <li class="col-6">
                                                    <div class="mb-2">
                                                        <label for="endDate" class="form-label">Ngày kết thúc</label>
                                                        <input type="date" class="form-control form-control-sm"
                                                            name="expire_date" id="endDate" data-filter
                                                            value="{{ request()->input('expire_date') ?? '' }}">
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

                    <div class="card-body" id="item_List">
                        <div class="listjs-table" id="customerList">

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
                                            <th>Mã banner</th>
                                            <th>Tiêu đề</th>
                                            <th>Ảnh</th>
                                            <th>Thứ tự</th>
                                            <th>Trạng thái</th>
                                            <th>Ngày tạo</th>
                                            <th>Ngày cập nhật</th>
                                            <th>Thời gian đã xóa</th>
                                        </tr>
                                    </thead>
                                    <tbody class="list form-check-all">
                                        @foreach ($banners as $banner)
                                            <tr>
                                                <th scope="row">
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="checkbox" name="itemID"
                                                            value="{{ $banner->id }}">
                                                    </div>
                                                </th>

                                                <td class="id">{{ $banner->id }}</td>
                                                <td class="customer_name">{{ $banner->title }}</td>
                                                <td class="phone">
                                                    @if ($banner->image)
                                                        <img src="{{ $banner->image }}" alt="" width="100px">
                                                    @else
                                                        <p>Không có ảnh</p>
                                                    @endif

                                                </td>
                                                <td class="date">{{ $banner->order }}</td>
                                                @if ($banner->status)
                                                    <td class="status"><span class="badge bg-success-subtle text-success">
                                                            Hoạt động
                                                        </span></td>
                                                @else
                                                    <td class="status"><span class="badge bg-danger-subtle text-danger">
                                                            Không hoạt động
                                                        </span></td>
                                                @endif

                                                <td class="date">{{ $banner->created_at }}</td>
                                                <td class="date">{{ $banner->updated_at }}</td>
                                                <td class="date">{{ $banner->deleted_at }}</td>
                                                
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

                            {{ $banners->links() }}

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
        var routeUrlFilter = "{{ route('admin.banners.index') }}";
        var routeDeleteAll = "{{ route('admin.banners.forceDelete', ':itemID')}}";
        var routeRestoreUrl = "{{ route('admin.banners.restoreDelete', ':itemID') }}";
    </script>
    <script src="{{ asset('assets/js/custom/custom.js') }}"></script>
    <script src="{{ asset('assets/js/common/checkall-option.js') }}"></script>
    <script src="{{ asset('assets/js/common/delete-all-selected.js') }}"></script>
    <script src="{{ asset('assets/js/common/restore-all-selected.js') }}"></script>
    <script src="{{ asset('assets/js/common/filter.js') }}"></script>
    <script src="{{ asset('assets/js/common/search.js') }}"></script>
    <script src="{{ asset('assets/js/common/handle-ajax-search&filter.js') }}"></script>
@endpush
