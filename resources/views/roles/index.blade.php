@extends('layouts.app')

@section('title', 'Quản lý vai trò')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                    <h4 class="mb-sm-0">{{ $title ?? '' }}</h4>

                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dasboard</a></li>
                            <li class="breadcrumb-item active">{{ $title ?? '' }}</li>
                        </ol>
                    </div>

                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header align-items-center d-flex">
                        <h4 class="card-title mb-0 flex-grow-1">{{ $subTitle ?? '' }}</h4>
                        <div class="d-flex gap-2">
                            <button class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#importModal">Import dữ liệu</button>
                            <a href="{{ route('admin.roles.create') }}" class="btn btn-primary">Thêm mới </a>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="live-preview">
                            <div class="table-responsive">
                                <table class="table table-striped table-nowrap align-middle mb-0">
                                    <thead>
                                    <tr>
                                        <th scope="col">#</th>
                                        <th scope="col">Vai trò</th>
                                        <th scope="col">Mô tả</th>
                                        <th scope="col">Ngày tạo</th>
                                        <th scope="col">Thao tác</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach ($roles as $role)
                                        <tr>
                                            <td class="fw-medium">{{ $loop->iteration }}</td>
                                            <td>{{ $role->name }}</td>
                                            <td>{{ $role->description }}</td>
                                            <td>{{ $role->created_at }}</td>
                                            <td>
                                                <a class="btn btn-sm btn-warning"
                                                   href="{{ route('admin.roles.edit', $role) }}"><span
                                                        class="ri-edit-box-line"></span></a>
                                                <a class="btn  btn-sm btn-danger sweet-confirm"
                                                   href="{{ route('admin.roles.destroy', $role) }}"><span
                                                        class="ri-delete-bin-7-line"></span></a>
                                            </td>
                                        </tr>
                                    @endforeach

                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <div class="modal fade" id="importModal" tabindex="-1" aria-labelledby="importModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="importModalLabel">Import Dữ Liệu</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <a href="{{ asset('storage/csv/roles_import_template.xlsx') }}" download class="btn btn-outline-primary btn-sm">Tải Mẫu</a>
                    </div>
                    <form action="{{ route('admin.roles.import') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="mb-3">
                            <label for="importFile" class="form-label">Chọn file để import:</label>
                            <input type="file" class="form-control" name="file" accept=".xlsx,.xls,.csv" required>
                        </div>
                        <button type="submit" class="btn btn-success">Tiến hành Import</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endSection
