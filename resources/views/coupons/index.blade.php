@extends('layouts.app')
@push('page-css')
    <!-- plugin css -->
    <link href="{{ asset('assets/libs/jsvectormap/css/jsvectormap.min.css') }}" rel="stylesheet" type="text/css" />
@endpush
@php
    $title = 'Danh sách coupon';
@endphp
@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                    <h4 class="mb-sm-0">Quản lí coupon</h4>

                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">Dashboard</a></li>
                            <li class="breadcrumb-item active">Danh sách coupon</li>
                        </ol>
                    </div>

                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title mb-1">Danh sách coupon</h4>
                    </div>

                    <div class="card-body">
                        <div class="listjs-table">
                            <div class="row g-4 mb-3">
                                <div class="col-sm-auto">
                                    <div>
                                        <a href="{{ route('admin.coupons.create') }}" class="btn btn-success add-btn"><i
                                                class="ri-add-line align-bottom me-1"></i> Thêm mới</a>
                                        <button class="btn btn-soft-danger" onClick="deleteMultiple()"><i
                                                class="ri-delete-bin-2-line"></i></button>
                                    </div>
                                </div>
                                <div class="col-sm">
                                    <div class="d-flex justify-content-sm-end">
                                        <div class="search-box ms-2">
                                            <form action="{{ route('admin.coupons.index') }}" method="get">
                                                <input type="text" name="query" class="form-control search"
                                                    placeholder="Search..." value="{{ old('query') }}">
                                                <i class="ri-search-line search-icon"></i>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="table-responsive table-card mt-3 mb-1">
                                <table class="table align-middle table-nowrap">
                                    <thead class="table-light">
                                        <tr>
                                            <th scope="col" style="width: 50px;">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" id="checkAll"
                                                        value="option">
                                                </div>
                                            </th>
                                            <th>ID</th>
                                            <th>User_id</th>
                                            <th>Name</th>
                                            <th>Code</th>
                                            <th>Discount</th>
                                            <th>Status</th>
                                            <th>Start_date</th>
                                            <th>Expire_date</th>
                                            <th>Used_count</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody class="list form-check-all">
                                        @foreach ($coupons as $coupon)
                                            <tr>
                                                <th scope="row">
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="checkbox" name="chk_child"
                                                            value="option1">
                                                    </div>
                                                </th>

                                                <td class="id">{{ $coupon->id }}</td>
                                                <td class="id">{{ $coupon->user_id }}</td>
                                                <td class="customer_name">{{ $coupon->name }}</td>
                                                <td class="date">{{ $coupon->code }}</td>
                                                <td class="date">{{ $coupon->discount_value }} ({{$coupon->discount_type}})</td>
                                                @if ($coupon->status)
                                                    <td class="status"><span
                                                            class="badge bg-success-subtle text-success text-uppercase">
                                                            Active
                                                        </span></td>
                                                @else
                                                    <td class="status"><span
                                                            class="badge bg-danger-subtle text-danger text-uppercase">
                                                            InActive
                                                        </span></td>
                                                @endif

                                                <td class="date">{{ $coupon->start_date }}</td>
                                                <td class="date">{{ $coupon->expire_date }}</td>
                                                <td class="date">{{ $coupon->used_count }}</td>
                                                <td>
                                                    <div class="d-flex gap-2">
                                                        <div class="remove">
                                                            <a href="{{ route('admin.coupons.show', $coupon->id) }}"
                                                                class="btn btn-sm btn-primary remove-item-btn">Chi tiết</a>
                                                        </div>
                                                        <div class="edit">
                                                            <a href="{{ route('admin.coupons.edit', $coupon->id) }}"
                                                                class="btn btn-sm btn-success edit-item-btn">Sửa</a>
                                                        </div>
                                                        <div class="remove">
                                                            <a href="{{ route('admin.coupons.destroy', $coupon->id) }}" class="btn btn-sm btn-danger sweet-confirm">Xoá</a>
                                                        </div>

                                                    </div>
                                                </td>


                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                                <div class="noresult" style="display: none">
                                    <div class="text-center">
                                        <lord-icon src="https://cdn.lordicon.com/msoeawqm.json" trigger="loop"
                                            colors="primary:#121331,secondary:#08a88a"
                                            style="width:75px;height:75px"></lord-icon>
                                        <h5 class="mt-2">Sorry! No Result Found</h5>
                                        <p class="text-muted mb-0">We've searched more than 150+ Orders We did not find any
                                            orders for you search.</p>
                                    </div>
                                </div>
                            </div>

                            {{ $coupons->links() }}
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