@extends('layouts.app')
@push('page-css')
    <link href="{{ asset('assets/css/custom.css') }}" rel="stylesheet" type="text/css" />
@endpush
@section('content')
    <div class="container-fluid">

        <!-- start page title -->
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                    <h4 class="mb-sm-0">{{ $title }}</h4>

                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                            <li class="breadcrumb-item active"><a href="">{{ $subTitle }}</a></li>
                        </ol>
                    </div>

                </div>
            </div>
        </div>
        <!-- end page title -->

        <!-- List-customer -->
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h4 class="card-title mb-0">{{ $subTitle }}</h4>
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
                                    <form>
                                        <div class="container">
                                            <div class="row">
                                                <li class="col-6">
                                                    <div class="mb-2">
                                                        <label for="startDate" class="form-label">Ngày bắt đầu</label>
                                                        <input type="date" class="form-control form-control-sm"
                                                            name="start_deleted" id="startDate" data-filter
                                                            value="{{ request()->input('start_deleted') ?? '' }}">
                                                    </div>
                                                </li>
                                                <li class="col-6">
                                                    <div class="mb-2">
                                                        <label for="endDate" class="form-label">Ngày kết thúc</label>
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
                                    </form>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <!-- Tìm kiếm nâng cao -->
                    <div id="advancedSearch" class="card-header" style="display:none;">
                        <div class="row">
                            <div class="col-md-3">
                                <label class="form-label">Tiêu đề</label>
                                <input class="form-control form-control-sm" name="title" type="text"
                                    value="{{ request()->input('title') ?? '' }}" placeholder="Nhập tiêu đề..."
                                    data-advanced-filter>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Tác giả</label>
                                <input class="form-control form-control-sm" name="user_name_post" type="text"
                                    value="{{ request()->input('name') ?? '' }}" placeholder="Nhập tên tác giả..."
                                    data-advanced-filter>
                            </div>
                            <div class="col-md-3 mb-3">
                                <label class="form-label">Danh mục</label>
                                <select class="form-select form-select-sm" name="category_id" id="statusItem"
                                    data-advanced-filter>
                                    <option value="">Chọn danh mục</option>
                                    @foreach ($categories as $category)
                                        <option @selected(request()->input('category_id') === $category->id) value="{{ $category->id }}">
                                            {{ $category->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label for="statusItem" class="form-label">Trạng thái</label>
                                <select class="form-select form-select-sm" name="status" id="statusItem"
                                    data-advanced-filter>
                                    <option value="">Chọn trạng thái</option>
                                    <option @selected(request()->input('status') === 'published') value="published">Xuất bản</option>
                                    <option @selected(request()->input('status') === 'private') value="private">Riêng tư</option>
                                    <option @selected(request()->input('status') === 'pending') value="pending">Chờ xử lí</option>
                                    <option @selected(request()->input('status') === 'draft') value="draft">Bản nháp</option>
                                </select>
                            </div>
                            <div class="mt-3 text-end">
                                <button class="btn btn-sm btn-success" type="reset" id="resetFilter">Reset</button>
                                <button class="btn btn-sm btn-primary" id="applyAdvancedFilter">Áp dụng</button>
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
                                            <input type="text" name="search_full" id="searchFull" class="form-control search"
                                                placeholder="Tìm kiếm..." data-search value="{{ request()->input('search_full') ?? '' }}">
                                            <button id="search-full" class="ri-search-line search-icon m-0 p-0 border-0"
                                                style="background: none;"></button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        
                            <div class="table-responsive table-card mt-3 mb-1">
                                <table class="table align-middle table-nowrap" id="customerTable">
                                    <thead class="table-light">
                                        <tr>
                                            <th scope="col" style="width: 50px;">
                                                <input type="checkbox" id="checkAll">
                                            </th>
                                            <th>ID</th>
                                            <th>Tiêu đề</th>
                                            <th>Ảnh bìa</th>
                                            <th>Tác giả</th>
                                            <th>Danh mục</th>
                                            <th>Trạng thái</th>
                                            <th>Thời gian xóa</th>
                                        </tr>
                                    </thead>
                                    <tbody class="list">
                                        @foreach ($posts as $post)
                                            <tr>
                                                <th scope="row">
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="checkbox" name="itemID"
                                                            value="{{ $post->id }}">
                                                    </div>
                                                </th>
                        
                                                <td class="customer_name">{{ $post->id }}</td>
                                                <td class="email">{{ $post->title }}</td>
                                                <td>
                                                    <img class="img-thumbnail" src="{{ $post->thumbnail }}" alt="Hình đại diện" width="100">
                                                </td>
                                                <td class="text-danger fw-bold">{{ $post->user->name }}</td>
                                                <td>{{ $post->category->name }}</td>
                                                <td class="status col-1">
                                                    @if ($post->status === 'published')
                                                        <span class="badge bg-success w-75">
                                                            Xuất bản
                                                        </span>
                                                    @elseif($post->status === 'pending')
                                                        <span class="badge bg-warning w-75">
                                                            Chờ xử lí
                                                        </span>
                                                    @elseif($post->status === 'draft')
                                                        <span class="badge bg-secondary w-75">
                                                            Bản nháp
                                                        </span>
                                                    @else
                                                        <span class="badge bg-danger w-75">
                                                            Riêng tư
                                                        </span>
                                                    @endif
                                                </td>
                                                <td>
                                                    {{ optional(\Carbon\Carbon::parse($post->deleted_at))->format('d/m/Y') ?? 'NULL' }}
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        
                            <div class="row justify-content-end">
                                {{ $posts->appends(request()->query())->links() }}
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
        var routeUrlFilter = "{{ route('admin.posts.list-post-delete') }}";
        var routeRestoreUrl = "{{ route('admin.posts.restoreDelete', ':itemID') }}";

        $(document).on('click', '#resetFilter', function() {
            window.location = routeUrlFilter;
        });
    </script>
    <script src="{{ asset('assets/js/custom/custom.js') }}"></script>
    <script src="{{ asset('assets/libs/list.pagination.js/list.pagination.min.js') }}"></script>
    <script src="{{ asset('assets/js/common/restore-all-selected.js') }}"></script>
    <script src="{{ asset('assets/js/common/filter.js') }}"></script>
    <script src="{{ asset('assets/js/common/search.js') }}"></script>
    <script src="{{ asset('assets/js/common/handle-ajax-search&filter.js') }}"></script>
    <!-- listjs init -->
    <script src="{{ asset('assets/js/pages/listjs.init.js') }}"></script>
    <script>
        $('#checkAll').on('change', function() {
            const isChecked = $(this).prop('checked');
            $('input[name="itemID"]').prop('checked', isChecked);
        });

        $(document).ready(function() {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $("#deleteSelected").click(function(event) {
                event.preventDefault();

                var selectedPosts = [];

                $('input[name="itemID"]:checked').each(function() {
                    selectedPosts.push($(this).val());
                });

                if (selectedPosts.length == 0) {
                    Swal.fire({
                        title: 'Chọn ít nhất 1 người dùng để xóa',
                        icon: 'warning',
                        confirmButtonText: 'OK'
                    });
                    return;
                }

                let deleteUrl = "{{ route('admin.posts.forceDelete', ':postID') }}".replace(':postID',
                    selectedPosts.join(','));

                Swal.fire({
                    title: "Bạn có muốn xóa ?",
                    text: "Bạn sẽ không thể khôi phục dữ liệu khi xoá!!",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#DD6B55",
                    confirmButtonText: "Đồng ý!!",
                    cancelButtonText: "Huỷ!!"
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            type: "DELETE",
                            url: deleteUrl,
                            success: function(data) {
                                if (data.status === 'success') {
                                    Swal.fire({
                                        title: 'Thao tác thành công!',
                                        text: data.message,
                                        icon: 'success'
                                    }).then((result) => {
                                        if (result.isConfirmed) {
                                            location.reload();
                                        }
                                    });
                                } else if (data.status === 'error') {
                                    Swal.fire({
                                        title: "Thao tác thất bại!",
                                        text: data.message,
                                        icon: 'error'
                                    });
                                }
                            },
                            error: function(data) {
                                console.log('Error:', data);
                                Swal.fire({
                                    title: "Thao tác thất bại!",
                                    text: data.responseJSON.message,
                                    icon: 'error'
                                });
                            }
                        });
                    }
                });
            });
        });
    </script>
@endpush
