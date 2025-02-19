@extends('layouts.app')

@push('page-css')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet"/>
@endpush

@section('content')
    <div class="container-fluid">

        <!-- start page title -->
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                    <h4 class="mb-sm-0">{{ $title ?? ''}}</h4>

                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                            <li class="breadcrumb-item active"><a href="">{{ $subTitle ?? '' }}</a></li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header align-items-center d-flex">
                        <h4 class="card-title mb-0 flex-grow-1">{{ $subTitle ?? '' }}</h4>
                    </div>

                    <form action="{{ route('admin.categories.store') }}" method="post" enctype="multipart/form-data">
                        @csrf
                        <div class="card-body">
                            <div class="live-preview">
                                <div class="row">
                                    <div class="col-xxl-12 col-md-12">
                                        <div class=" mb-3">
                                            <label for="placeholderInput" class="form-label">Tên danh mục</label>
                                            <input type="text" class="form-control" id="placeholderInput"
                                                   placeholder="Nhập tên danh mục" name="name"
                                                   value="{{ old('name') }}">
                                        </div>
                                        @if ($errors->has('name'))
                                            <span class="text-danger">{{ $errors->first('name') }}</span>
                                        @endif
                                    </div>

                                    <div class="col-xxl-12 col-md-12">
                                        <label for="placeholderInput" class="form-label">Danh mục gốc</label>
                                        <select class="form-control mb-3 select2-categories" name="parent_id">
                                            <option value="">--- Trống ---</option>
                                            @foreach($categories as $category)
                                                @if(is_null($category->parent_id))
                                                    @include('categories.category_option', ['category' => $category, 'level' => 0, 'oldParentId' => old('parent_id')])
                                                @endif
                                            @endforeach
                                        </select>
                                        @if ($errors->has('parent_id'))
                                            <span class="text-danger">{{ $errors->first('parent_id') }}</span>
                                        @endif
                                    </div>
                                    <div class="col-xxl-12 col-md-12">
                                        <label for="placeholderInput" class="form-label">Trạng thái</label>
                                        <div class="form-check form-check-secondary mb-3">
                                            <input class="form-check-input" type="checkbox" id="formCheck7"
                                                   name="status" value="1" checked>
                                            <label class="form-check-label" for="formCheck7">
                                                Active
                                            </label>
                                        </div>
                                    </div>

                                </div>
                                <div class="mt-3">
                                    <button type="submit" class="btn btn-primary waves-effect waves-light">Thêm
                                        mới
                                    </button>
                                    <button type="reset" class="btn btn-secondary">Nhập lại</button>
                                    <a href="{{ route('admin.categories.index') }}" type="button"
                                       class="btn btn-danger waves-effect waves-lightm ">Quay lại</a>
                                </div>
                            </div>
                        </div>
                    </form>

                </div>
            </div>
            <!--end col-->
        </div>
        <!-- end row -->

    </div>
@endsection


