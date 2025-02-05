@extends('layouts.app')
@push('page-css')
    <!-- plugin css -->
    <link href="{{ asset('assets/libs/jsvectormap/css/jsvectormap.min.css') }}" rel="stylesheet" type="text/css" />
@endpush
@php
    $title = 'Chi tiết banner';
@endphp
@section('content')
    <div class="row">
        <div class="col-lg-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0 ps-2">Quản lí banner</h4>
                <div class="page-title-right pe-3">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">Dashboard</a></li>
                        <li class="breadcrumb-item active"><a href="{{route('admin.banners.index')}}">Danh sách banner</a></li>
                        <li class="breadcrumb-item active"><a href="{{route('admin.banners.show',$banner->id)}}">Chi tiết banner</a></li>
                    </ol>
                </div>

            </div>
        </div>
    </div>
    <div class="container-fluid">

        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title mb-0">Chi tiết banner</h4>
                        @if (session()->has('success') && session()->get('success'))
                            <div class="alert alert-success" role="alert">
                                Thao tác thành công
                            </div>
                        @endif
                        @if (session()->has('success') && !session()->get('success'))
                            <div class="alert alert-danger" role="alert">
                                <strong>Thao tác không thành công</strong>
                            </div>
                        @endif
                    </div>
                    <div class="card-body">
                        <div>
                            <form method="post" enctype="multipart/form-data">
                                @csrf
                                <div class="mb-3">
                                    <label class="form-label">Tiêu đề</label>
                                    <input type="text" @readonly(true) name="title" class="form-control"
                                        value="{{ $banner->title }}">
                                    @error('title')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Đường dẫn</label>
                                    <input type="text" @readonly(true) name="redirect_url" class="form-control"
                                        value="{{ $banner->redirect_url }}">
                                    @error('redirect_url')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Ảnh</label>
                                    <input type="file" disabled name="image" class="form-control">
                                    <img src="{{ $banner->image }}" alt="" srcset="" width="200px">
                                    @error('image')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Nội dung</label>
                                    <input type="text" @readonly(true) name="content" class="form-control"
                                        value="{{ $banner->content }}">
                                    @error('content')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Thứ tự</label>
                                    <input type="int" @readonly(true) name="order" class="form-control"
                                        value="{{ $banner->order }}">
                                    @error('order')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Trạng thái</label>
                                    <select disabled name="status" class="form-select" value="{{ old('status') }}">
                                        <option value="1" <?php echo $banner->status == 1 ? 'selected' : ''; ?>>Hoạt động</option>
                                        <option value="0" <?php echo $banner->status == 0 ? 'selected' : ''; ?>>Không hoạt động</option>
                                    </select>
                                    @error('status')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                                <a class="btn btn-success add-btn" href="{{ route('admin.banners.index') }}">Trở về trang
                                    danh sách</a>
                            </form>
                        </div>
                    </div><!-- end card -->
                </div>
                <!-- end col -->
            </div>
            <!-- end col -->
        </div>


    </div>
@endsection
@push('page-scripts')
    <!-- apexcharts -->
    <script src="{{ asset('assets/libs/apexcharts/apexcharts.min.js') }}"></script>

    <!-- Vector map-->
    <script src="{{ asset('assets/libs/jsvectormap/js/jsvectormap.min.js') }}"></script>
    <script src="{{ asset('assets/libs/jsvectormap/maps/world-merc.js') }}"></script>

    <!-- Dashboard init -->
    <script src="{{ asset('assets/js/pages/dashboard-analytics.init.js') }}"></script>
@endpush