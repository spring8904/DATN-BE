@extends('layouts.app')


@section('content')
    <div class="container-fluid">

        <!-- start page title -->
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                    <h4 class="mb-sm-0">Listjs</h4>

                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="javascript: void(0);">Tables</a></li>
                            <li class="breadcrumb-item active">Listjs</li>
                        </ol>
                    </div>

                </div>
            </div>
        </div>
        <!-- end page title -->

        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title mb-0">Chi tiết hoa hồng</h4>
                    </div><!-- end card header -->

                    <div class="card-body">
                        <div class="listjs-table" id="customerList">
                            <div class="row g-4 mb-3">
                                <div class="col-sm-auto">
                                    <div>
                                        <a href="{{ route('admin.commissions.index') }}" type="button"
                                            class="btn btn-danger add-btn">
                                            Quay lại</a>

                                    </div>
                                </div>

                            </div>

                            <div class="table-responsive table-card mt-3 mb-1">
                                <table class="table align-middle table-nowrap" id="customerTable">
                                    <thead class="table-light">
                                        <tr>
                                            <th>ID</th>
                                            <th>Mức độ khó</th>
                                            <th>Phần trăm của hệ thống</th>
                                            <th>Phần trăm của giáo viên</th>
                                            <th>Ngày tạo</th>
                                            <th>Ngày cập nhập</th>
                                        </tr>
                                    </thead>

                                    <tbody class="list form-check-all">

                                        <tr>

                                            <td>{{ $commission->id }}</td>
                                            <td>{{ $commission->difficulty_level }}</td>
                                            <td>{{ $commission->system_percentage }}</td>
                                            <td>{{ $commission->teacher_percentage }}</td>
                                            <td>{{ $commission->created_at }}</td>
                                            <td>{{ $commission->updated_at }}</td>

                                        </tr>

                                    </tbody>

                                </table>

                            </div>


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
<div>
    <!-- People find pleasure in different ways. I find it in keeping my mind clear. - Marcus Aurelius -->
</div>
