@extends('layouts.app')

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
                        <div class="dropdown">
                            <button class="btn btn-sm btn-primary" type="button" id="filterDropdown"
                                data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="ri-filter-2-line"></i>
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="filterDropdown">
                                <div class="container">
                                    <li>
                                        <select class="form-select form-select-sm mb-2">
                                            <option value="">Tất cả trạng thái</option>
                                            <option value="active">Hoạt động</option>
                                            <option value="inactive">Không hoạt động</option>
                                            <option value="banned">Bị khóa</option>
                                        </select>
                                    </li>
                                    <li>
                                        <div class="mb-2">
                                            <label for="startDate" class="form-label">Từ ngày</label>
                                            <input type="date" class="form-control form-control-sm" id="startDate">
                                        </div>
                                    </li>
                                    <li>
                                        <div class="mb-2">
                                            <label for="endDate" class="form-label">Đến ngày</label>
                                            <input type="date" class="form-control form-control-sm" id="endDate">
                                        </div>
                                    </li>
                                    <li>
                                        <button class="btn btn-sm btn-primary w-100">Áp dụng</button>
                                    </li>
                                </div>
                            </ul>
                        </div>
                    </div>
                    <!-- end card header -->
                    <div class="card-body">
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
                                            <form action="{{ route('admin.posts.index') }}" method="GET">
                                                <input type="text" name="searchPost" class="form-control search"
                                                    id="search-options" placeholder="Tìm kiếm..."
                                                    value="{{ request('searchUser') }}">
                                                <button type="submit" class="ri-search-line search-icon m-0 p-0 border-0"
                                                    style="background: none;"></button>
                                            </form>
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
                                            <th>Trạng thái</th>
                                            <th>Ngày đăng tải</th>
                                            <th>Thời gian xóa</th>
                                        </tr>
                                    </thead>
                                    <tbody class="list">
                                        @foreach ($posts as $post)
                                            <tr>
                                                <th scope="row">
                                                    <div class="form-check">
                                                        <input class="form-check-input" id="checkAll" type="checkbox"
                                                            name="itemID" value="{{ $post->id }}">
                                                    </div>
                                                </th>

                                                <td class="customer_name">{{ $post->id }}</td>
                                                <td class="email">{{ $post->title }}</td>
                                                <td>
                                                    <img class="img-thumbnail" src="{{ $post->thumbnail }}"
                                                        alt="Hình đại diện" width="100">
                                                </td>
                                                <td class="email">{{ $post->user->name }}</td>

                                                <td class="status">
                                                    @if ($post->status === 'published')
                                                        <span class="badge bg-success w-100">
                                                            Đã xuất bản
                                                        </span>
                                                    @elseif($post->status === 'pending')
                                                        <span class="badge bg-warning w-100">
                                                            Đang chờ xử lí
                                                        </span>
                                                    @elseif($post->status === 'draft')
                                                        <span class="badge bg-secondary w-100">
                                                            Bản nháp
                                                        </span>
                                                    @else
                                                        <span class="badge bg-danger w-100">
                                                            Riêng tư
                                                        </span>
                                                    @endif
                                                </td>

                                                <td>
                                                    {{ $post->published_at ? \Carbon\Carbon::parse($post->published_at)->format('d/m/Y H:i:s') : 'NULL' }}
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
        var routeRestoreUrl = "{{ route('admin.posts.restoreDelete', ':itemID') }}";
    </script>
    <script src="{{ asset('assets/libs/list.pagination.js/list.pagination.min.js') }}"></script>
    <script src="{{ asset('assets/js/common/restore-all-selected.js') }}"></script>
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
