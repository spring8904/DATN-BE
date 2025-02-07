@extends('layouts.app')

@push('page-css')
    <link href="{{ asset('assets/css/custom.css') }}" rel="stylesheet" type="text/css"/>
@endpush

@section('content')
    <div class="container-fluid">

        <!-- start page title -->
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                    <h4 class="mb-sm-0">{{ $title ?? '' }}</h4>

                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                            <li class="breadcrumb-item active"><a
                                >{{ $title ?? '' }}</a></li>
                        </ol>
                    </div>

                </div>
            </div>
        </div>
        <!-- end page title -->

        <!-- social-customer -->
        <div class="row mb-2">
            <div class="col-12 col-sm-6 col-md-6">
                <div class="card text-center h-75">
                    <div class="card-body">
                        <h5 class="card-title">Tổng số 1</h5>
                        <p class="card-text fs-4">2</p>
                    </div>
                </div>
            </div>
            <div class="col-12 col-sm-6 col-md-6">
                <div class="card text-center h-75">
                    <div class="card-body">
                        <h5 class="card-title">Tổng số 1</h5>
                        <p class="card-text fs-4">2</p>
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
                        <h4 class="card-title mb-0">{{ $subTitle ?? '' }}</h4>
                        <div class="d-flex gap-2">
                            <button class="btn btn-sm btn-success">Export dữ liệu</button>
                            <button class="btn btn-sm btn-primary" id="toggleAdvancedSearch">
                                Tìm kiếm nâng cao
                            </button>
                            <div class="dropdown">
                                <button class="btn btn-sm btn-primary" type="button" id="filterDropdown"
                                        data-bs-toggle="dropdown" aria-expanded="false">
                                    <i class="ri-filter-2-line"></i>
                                </button>
                                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="filterDropdown">
                                    <div class="container">
                                        <li>
                                            <select class="form-select form-select-sm mb-2" name="status" data-filter>
                                                <option value="">Tất cả trạng thái</option>
                                                <option value="active">Hoạt động</option>
                                                <option value="inactive">Không hoạt động</option>
                                                <option value="blocked">Bị khóa</option>
                                            </select>
                                        </li>
                                        <li>
                                            <div class="mb-2">
                                                <label for="startDate" class="form-label">Từ ngày</label>
                                                <input type="date" class="form-control form-control-sm"
                                                       name="start_date" data-filter id="startDate"
                                                       value="{{ request()->input('start_date') ?? '' }}">
                                            </div>
                                        </li>
                                        <li>
                                            <div class="mb-2">
                                                <label for="endDate" class="form-label">Đến ngày</label>
                                                <input type="date" class="form-control form-control-sm" id="endDate"
                                                       name="end_date" data-filter
                                                       value="{{ request()->input('end_date') ?? '' }}">
                                            </div>
                                        </li>
                                        <li>
                                            <button class="btn btn-sm btn-primary w-100" id="applyFilter">Áp dụng
                                            </button>
                                        </li>
                                    </div>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <!-- Tìm kiếm nâng cao -->
                    <div id="advancedSearch" class="card-header" style="display:none;">
                        <div class="row">
                            <div class="col-md-3">
                                <label class="form-label">Mã người dùng</label>
                                <input class="form-control form-control-sm" type="text"
                                       placeholder="Nhập mã người dùng...">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Tên khách hàng</label>
                                <input class="form-control form-control-sm" type="text"
                                       placeholder="Nhập tên khách hàng...">
                            </div>
                            <div class="col-md-3 mb-3">
                                <label class="form-label">Email</label>
                                <input class="form-control form-control-sm" type="email"
                                       placeholder="Nhập email...">
                            </div>
                            <div class="col-md-3 mb-3">
                                <label class="form-label">Số điện thoại</label>
                                <input class="form-control form-control-sm" type="text"
                                       placeholder="Nhập số điện thoại...">
                            </div>
                            <div class="col-md-3">
                                <label for="statusItem" class="form-label">Trạng thái</label>
                                <select class="form-select form-select-sm" name="status" id="statusItem" data-filter>
                                    <option value="">Tất cả trạng thái</option>
                                    <option value="completed">Hoàn thành</option>
                                    <option value="pending">Chờ xử lý</option>
                                    <option value="failed">Thất bại</option>
                                </select>
                            </div>
                            <div class="mt-3 text-end">
                                <button class="btn btn-sm btn-primary">Áp dụng</button>
                            </div>
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
                                        <th>Tên khoá học</th>
                                        <th>Giảng viên</th>
                                        <th>Hình ảnh</th>
                                        <th>Giá</th>
                                        <th>Ngày gửi yêu cầu</th>
                                        <th>Trạng thái</th>
                                        <th>Người kiểm duyệt</th>
                                        <th>Ngày kiểm duyệt</th>
                                        <th>Hành động</th>
                                    </tr>
                                    </thead>
                                    <tbody class="list">
                                    @foreach($approvals as $approval)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ \Illuminate\Support\Str::limit($approval->course->name, 50) }}</td>
                                            <td>{{ $approval->user->name }}</td>
                                            <td>
                                                <img style="height: 80px" src="{{ $approval->course->thumbnail }}" alt=""
                                                     class="w-100 object-fit-cover">
                                            </td>
                                            <td>{{ number_format($approval->course->price) }}</td>
                                            <td>{{ $approval->request_date ?? '<span class="btn btn-sm btn-soft-warning">Chưa kiểm duyệt</span>' }}</td>
                                            <td>
                                                @if($approval->status == 'pending')
                                                    <span class="btn btn-sm btn-soft-warning">Chờ xử lý</span>
                                                @elseif($approval->status == 'approved')
                                                    <span class="btn btn-sm btn-soft-success">Đã kiểm duyệt</span>
                                                @else
                                                    <span class="btn btn-sm btn-soft-danger">Từ chối</span>
                                                @endif
                                            </td>
                                            <td>
                                                {!!   $approval->approver->name  ?? '<span class="btn btn-sm btn-soft-warning">Chưa kiểm duyệt</span>' !!}
                                            </td>
                                            <td>
                                                {!! $approval->approved_at ?? '<span class="btn btn-sm btn-soft-warning">Chưa kiểm duyệt</span>' !!}
                                            </td>
                                            <td>
                                                <a href="{{ route('admin.approvals.courses.show', $approval->id) }}"
                                                   class="btn btn-sm btn-soft-secondary ">Chi tiết</a>
                                            </td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>

                            <div class="row justify-content-end">
                                {{ $approvals->appends(request()->query())->links() }}
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

@endpush
