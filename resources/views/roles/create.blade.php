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
                    </div>
                    <div class="card-body">
                        <form action="{{ route('admin.roles.store') }}" method="POST" class="row g-3">
                            @csrf
                            <div class="col-md-12">
                                <label class="form-label">Tên vai trò</label>
                                <input type="name" class="form-control mb-2" placeholder="Nhập tên vai trò..."
                                    value="{{ old('name') }}" name="name">

                                @if ($errors->has('name'))
                                    <span class="text-danger">{{ $errors->first('name') }}</span>
                                @endif

                            </div>
                            <div class="col-md-12">
                                <label for="inputEmail4" class="form-label">Mô tả</label>
                                <textarea class="form-control mb-2" placeholder="Nhập mô tả..." name="description" id="">{{ old('description') }}</textarea>

                                @if ($errors->has('description'))
                                    <span class="text-danger">{{ $errors->first('description') }}</span>
                                @endif
                            </div>
                            {{-- <div class="col-12">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="gridCheck">
                                    <label class="form-check-label" for="gridCheck">
                                        Check me out
                                    </label>
                                </div>
                            </div> --}}
                            <div class="col-12">
                                <div class="">
                                    <button type="submit" class="btn btn-primary">Thêm mới</button>
                                    <button type="reset" class="btn btn-secondary ms-2">Nhập lại</button>
                                    <a class="btn btn-dark ms-2" href="{{ route('admin.roles.index') }}">Danh sách</a>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

    </div>
@endSection
