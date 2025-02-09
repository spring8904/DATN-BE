@extends('layouts.app')


@section('content')
    <div class="container-fluid">

        <!-- start page title -->
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                    <h4 class="mb-sm-0">Quản lí banner đã xóa</h4>

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
                                                <li class="col-6">
                                                    <div class="mb-2">
                                                        <label for="startDate" class="form-label">Ngày bắt đầu xóa</label>
                                                        <input type="date" class="form-control form-control-sm"
                                                            name="start_deleted" id="startDate" data-filter
                                                            value="{{ request()->input('start_deleted') ?? '' }}">
                                                    </div>
                                                </li>
                                                <li class="col-6">
                                                    <div class="mb-2">
                                                        <label for="endDate" class="form-label">Ngày kết thúc xóa</label>
                                                        <input type="date" class="form-control form-control-sm"
                                                            name="end_deleted" id="endDate" data-filter
                                                            value="{{ request()->input('end_deleted') ?? '' }}">
                                                    </div>
                                                </li>
                                            </div>
                                            <li class="mt-2 d-flex gap-1">
                                                <button class="btn btn-sm btn-success flex-grow-1" type="reset"
                                                    id="resetFilter">Reset</button>
                                                <button class="btn btn-sm btn-primary flex-grow-1" id="applyFilter">Áp
                                                    dụng</button>
                                            </li>
                                        </div>
                                    </div>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <!-- Tìm kiếm nâng cao -->
                    <div id="advancedSearch" class="card-header" style="display:none;">
                        <form>
                            <div class="row">
                                <div class="col-md-4">
                                    <label class="form-label">Mã banner</label>
                                    <input class="form-control form-control-sm" name="id" type="text"
                                        value="{{ request()->input('id') ?? '' }}" placeholder="Nhập mã banner..."
                                        data-advanced-filter>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">Tiêu đề</label>
                                    <input class="form-control form-control-sm" name="title" type="text"
                                        value="{{ request()->input('title') ?? '' }}" placeholder="Nhập tiêu đề..."
                                        data-advanced-filter>
                                </div>
                                <div class="col-md-4">
                                    <label for="statusItem" class="form-label">Trạng thái</label>
                                    <select class="form-select form-select-sm" name="status" id="statusItem"
                                        data-advanced-filter>
                                        <option value="">Chọn trạng thái</option>
                                        <option @selected(request()->input('status') === '1') value="1">Hoạt động</option>
                                        <option @selected(request()->input('status') === '0') value="0">Không hoạt động</option>
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
                                            <input type="text" name="search_full" id="searchFull"
                                                class="form-control search" placeholder="Tìm kiếm..." data-search
                                                value="{{ request()->input('search_full') ?? '' }}">
                                            <button id="search-full" class="ri-search-line search-icon m-0 p-0 border-0"
                                                style="background: none;"></button>
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
                                            <th>Thời gian xóa</th>
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

                            {{ $banners->appends(request()->query())->links() }}

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
        var routeUrlFilter = "{{ route('admin.banners.deleted') }}";
        var routeDeleteAll = "{{ route('admin.banners.forceDelete', ':itemID') }}";
        var routeRestoreUrl = "{{ route('admin.banners.restoreDelete', ':itemID') }}";

        $(document).on('click', '#resetFilter', function() {
            window.location = routeUrlFilter;
        });
    </script>
    <script src="{{ asset('assets/js/custom/custom.js') }}"></script>
    <script src="{{ asset('assets/js/common/checkall-option.js') }}"></script>
    <script src="{{ asset('assets/js/common/delete-all-selected.js') }}"></script>
    <script src="{{ asset('assets/js/common/restore-all-selected.js') }}"></script>
    <script src="{{ asset('assets/js/common/filter.js') }}"></script>
    <script src="{{ asset('assets/js/common/search.js') }}"></script>
    <script src="{{ asset('assets/js/common/handle-ajax-search&filter.js') }}"></script>
@endpush
