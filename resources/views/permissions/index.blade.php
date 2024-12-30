@extends('layouts.app')

@section('title', $title)

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
                    </div>
                    <div class="card-body">
                        <form action="{{ route('admin.permissions.store') }}" method="POST" class="row g-3">
                            @csrf
                            <div class="col-md-12">
                                <label class="form-label">Tên quyền</label>
                                <input type="text" name="name" class="form-control mb-2"
                                    placeholder="Nhập tên quyền..." value="{{ old('name') }}">

                                @if ($errors->has('name'))
                                    <span class="text-danger">{{ $errors->first('name') }}</span>
                                @endif

                            </div>

                            <div class="col-md-12">
                                <label for="inputEmail4" class="form-label">Mô tả</label>
                                <input type="text" class="form-control mb-2" placeholder="Nhập mô tả..."
                                    value="{{ old('description') }}" name="description">

                                @if ($errors->has('description'))
                                    <span class="text-danger">{{ $errors->first('description') }}</span>
                                @endif
                            </div>
                            <div class="col-12">
                                <div class="">
                                    <button type="submit" class="btn btn-primary">Thêm mới</button>
                                    <button type="reset" class="btn btn-secondary ms-2">Nhập lại</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header align-items-center d-flex">
                        <h4 class="card-title mb-0 flex-grow-1">{{ $subTitle ?? '' }}</h4>
                    </div>
                    <div class="card-body">
                        <div class="live-preview">
                            <div class="table-responsive">
                                <table class="table table-striped table-nowrap align-middle mb-0">
                                    <thead>
                                        <tr>
                                            <th scope="col">#</th>
                                            <th scope="col">Quyền</th>
                                            <th scope="col">Mô tả</th>
                                            <th scope="col">Ngày tạo</th>
                                            <th scope="col">Thao tác</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($permissions as $guardName => $groupedPermissions)
                                            <td class="fw-bold" colspan="5">Module {{ Str::ucfirst($guardName) }}</td>
                                            @foreach ($groupedPermissions as $permission)
                                                <tr>
                                                    <td class="fw-medium">{{ $loop->iteration }}</td>
                                                    <td>{{ $permission->name }}</td>
                                                    <td>{{ $permission->description }}</td>
                                                    <td>{{ $permission->created_at }}</td>
                                                    <td>
                                                        <a href="{{ route('admin.permissions.edit', $permission) }}">
                                                            <button class="btn btn-sm btn-warning edit-item-btn">
                                                                <span class="ri-edit-box-line"></span>
                                                            </button></a>


                                                        <a href="{{ route('admin.permissions.destroy', $permission->id) }}"
                                                            class="sweet-confirm btn btn-sm btn-danger ">
                                                            <span class="ri-delete-bin-7-line"></span>
                                                        </a>
                                                    </td>
                                                </tr>
                                            @endforeach
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
@endSection
