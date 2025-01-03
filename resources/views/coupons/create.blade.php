@extends('layouts.app')
@push('page-css')
    <!-- plugin css -->
    <link href="{{ asset('assets/libs/jsvectormap/css/jsvectormap.min.css') }}" rel="stylesheet" type="text/css" />
@endpush
@php
    $title = 'Thêm mới coupon';
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
                    <li class="breadcrumb-item active"><a href="{{route('admin.coupons.create')}}">Thêm mới coupon</a></li>
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
                        <h4 class="card-title mb-0">Thêm mới coupon</h4>
                    </div>
                    <div class="card-body">
                        <div>
                            <form action="{{ route('admin.coupons.store') }}" method="post" enctype="multipart/form-data">
                                @csrf
                                <div class="d-flex">
                                    <div class="mb-3 col-6 pe-3">
                                        <label class="form-label">Name</label>
                                        <input type="text" name="name" class="form-control" value="{{ old('name') }}">
                                        @error('name')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="mb-3 col-6">
                                        <label class="form-label">Code</label>
                                        <input type="text" name="code" class="form-control"
                                            value="{{ old('code') }}">
                                        @error('code')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="d-flex">
                                    <div class="mb-3 col-6 pe-3">
                                        <label class="form-label">Discount_type</label>
                                        <select name="discount_type" class="form-control" value="{{ old('content') }}">
                                            <option value="fixed">Fixed</option>
                                            <option value="percentage">Percentage</option>
                                        </select>
                                        @error('discount_type')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="mb-3 col-6">
                                        <label class="form-label">Discount_value</label>
                                        <input type="number" name="discount_value" class="form-control" value="{{ old('discount_value') }}">
                                        @error('discount_value')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="d-flex">
                                    <div class="mb-3 col-6 pe-3">
                                        <label class="form-label">Used_count</label>
                                        <input type="int" name="used_count" class="form-control" value="{{ old('used_count') }}">
                                        @error('used_count')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                        <div class="mb-3 col-6">
                                        <label class="form-label">Status</label>
                                        <select name="status" class="form-select" value="{{ old('status') }}">
                                            <option value="" selected>Chọn trạng thái</option>
                                            <option value="1">Active</option>
                                            <option value="0">InActive</option>
                                        </select>
                                        @error('status')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="d-flex">
                                    <div class="mb-3 col-6 pe-3">
                                        <label class="form-label">start_date</label>
                                        <input type="date" name="start_date" class="form-control" value="{{ old('start_date') }}">
                                        @error('start_date')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="mb-3 col-6">
                                        <label class="form-label">Expire_date</label>
                                        <input type="date" name="expire_date" class="form-control" value="{{ old('expire_date') }}">
                                        @error('expire_date')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                        
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Description</label>
                                    <textarea name="description" class="form-control" value="{{ old('description') }}"></textarea>
                                    @error('description')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                                <button type="submit" class="btn btn-soft-primary waves-effect waves-light">Thêm
                                    mới</button>
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