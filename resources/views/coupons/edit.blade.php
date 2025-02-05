@extends('layouts.app')
@push('page-css')
    <!-- plugin css -->
    <link href="{{ asset('assets/libs/jsvectormap/css/jsvectormap.min.css') }}" rel="stylesheet" type="text/css" />
@endpush
@php
    $title = 'Cập nhật coupon';
@endphp
@section('content')
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0 ps-2">Quản lí coupon</h4>

            <div class="page-title-right pe-3">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">Dashboard</a></li>
                    <li class="breadcrumb-item active"><a href="{{route('admin.coupons.index')}}">Danh sách coupon</a></li>
                    <li class="breadcrumb-item active"><a href="{{route('admin.coupons.create')}}">Cập nhật coupon</a></li>
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
                        <h4 class="card-title mb-0">Cập nhật coupon</h4>
                    </div>
                    <div class="card-body">
                        <div>
                            <form action="{{ route('admin.coupons.update',$coupon->id) }}" method="post" enctype="multipart/form-data">
                                @csrf
                                @method('PUT')
                                <div class="d-flex">
                                    <div class="mb-3 col-6 pe-3">
                                        <label class="form-label">Tên mã giảm giá</label>
                                        <input type="text" name="name" class="form-control" value="{{ $coupon->name }}">
                                        @error('name')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="mb-3 col-6">
                                        <label class="form-label">Mã giảm giá</label>
                                        <input type="text" name="code" class="form-control"
                                            value="{{ $coupon->code }}">
                                        @error('code')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="d-flex">
                                    <div class="mb-3 col-6 pe-3">
                                        <label class="form-label">Loại giảm giá</label>
                                        <select name="discount_type" class="form-control" value="{{ old('discount_type') }}">
                                            <option value="fixed" <?=$coupon->discount_type =="fixed" ? 'selected' : ""?> >Fixed</option>
                                            <option value="percentage" <?=$coupon->discount_type =="percentage" ? 'selected' : ""?>>Percentage</option>
                                        </select>
                                        @error('discount_type')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="mb-3 col-6">
                                        <label class="form-label">Giá trị giảm giá</label>
                                        <input type="number" name="discount_value" class="form-control" value="{{ $coupon->discount_value }}">
                                        @error('discount_value')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="d-flex">
                                    <div class="mb-3 col-6 pe-3">
                                        <label class="form-label">Số lượt sử dụng</label>
                                        <input type="int" name="used_count" class="form-control" value="{{ $coupon->used_count }}">
                                        @error('used_count')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                        <div class="mb-3 col-6">
                                        <label class="form-label">Trạng Thái</label>
                                        <select name="status" class="form-select" value="{{ old('status') }}">
                                            <option value="" selected>Chọn trạng thái</option>
                                            <option value="1" <?=$coupon->status =="1" ? 'selected' : ""?>>Hoạt động</option>
                                            <option value="0" <?=$coupon->status =="0" ? 'selected' : ""?>>Không hoạt động</option>
                                        </select>
                                        @error('status')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="d-flex">
                                    <div class="mb-3 col-6 pe-3">
                                        <label class="form-label">Ngày bắt đầu</label>
                                        <input type="date" name="start_date" class="form-control" value="{{ $coupon->start_date }}">
                                        @error('start_date')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="mb-3 col-6">
                                        <label class="form-label">Ngày kết thúc</label>
                                        <input type="date" name="expire_date" class="form-control" value="{{ $coupon->expire_date }}">
                                        @error('expire_date')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                        
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Mô tả</label>
                                    <textarea name="description" class="form-control">{{$coupon->description}}</textarea>
                                    @error('description')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                                <button type="submit" class="btn btn-soft-primary waves-effect waves-light">Cập nhật</button>
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