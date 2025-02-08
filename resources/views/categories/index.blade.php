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
                        <h4 class="card-title mb-0">Quản lí danh mục</h4>
                    </div><!-- end card header -->

                    <div class="card-body">
                        <div class="listjs-table" id="customerList">
                            <div class="row g-4 mb-3">
                                <div class="col-sm-auto">
                                    <div>
                                        <a href="{{ route('admin.categories.create') }}" type="button"
                                            class="btn btn-success add-btn"><i class="ri-add-line align-bottom me-1"></i>
                                            Thêm</a>
                                        {{-- <button  data-bs-toggle="modal"
                                            id="create-btn" data-bs-target="#showModal"> </button> --}}
                                        <button class="btn btn-soft-danger" onClick="deleteMultiple()"><i
                                                class="ri-delete-bin-2-line"></i></button>
                                    </div>
                                </div>

                                <div class="col-sm">
                                    <div class="d-flex justify-content-sm-end">
                                        <div class="search-box ms-2">
                                            <input type="text" class="form-control search" placeholder="Search...">
                                            <i class="ri-search-line search-icon"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="table-responsive table-card mt-3 mb-1">
                                <table class="table align-middle table-nowrap" id="customerTable">
                                    <thead class="table-light">
                                        <tr>
                                            <th scope="col" style="width: 50px;">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" id="checkAll"
                                                        value="option">
                                                </div>
                                            </th>
                                            <th>ID</th>
                                            <th>Tên danh mục</th>
                                            <th>Slug</th>
                                            <th>Danh mục cha</th>
                                            <th>Trạng thái</th>


                                            <th>Biểu tượng</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    @foreach ($categories as $category)
                                        <tbody class="list form-check-all">
                                            <tr>
                                                <th scope="row">
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="checkbox" name="chk_child"
                                                            value="option1">
                                                    </div>
                                                </th>
                                                <td class="id" style="display:none;"><a href="javascript:void(0);"
                                                        class="fw-medium link-primary">#VZ2101</a></td>
                                                <td>{{ $category->id }}</td>
                                                <td>{{ $category->name }}</td>
                                                <td>{{ $category->slug }}</td>
                                                <td>{{ $category->parent ? $category->parent->name : 'Không có' }}</td>
                                                <td>
                                                    @if ($category->status)
                                                        <span
                                                            class="badge bg-success-subtle text-success text-uppercase">Active<span>
                                                            @else
                                                                <span
                                                                    class="badge bg-danger-subtle text-success text-uppercase">No
                                                                    active<span>
                                                    @endif
                                                </td>
                                                <td class="phone">
                                                    <img src="{{ $category->icon }}" alt="{{ $category->name }}"
                                                        width="100">
                                                </td>
                                                <td>
                                                    <div class="d-flex gap-2">
                                                        <div>
                                                            <a href="{{ route('admin.categories.show', $category->id) }}">
                                                                <button class="btn btn-sm btn-warning edit-item-btn">
                                                                    <span class="ri-edit-box-line"></span>
                                                                </button>
                                                            </a>
                                                        </div>

                                                        <div class="edit">
                                                            <a href="{{ route('admin.categories.edit', $category->id) }}">
                                                                <button class="btn btn-sm btn-info edit-item-btn">
                                                                    <span class="ri-folder-user-line"></span>
                                                                </button>
                                                            </a>
                                                        </div>

                                                        <div class="remove">
                                                            <a href="{{ route('admin.categories.destroy', $category->id) }}"
                                                                class="sweet-confirm btn btn-sm btn-danger remove-item-btn">
                                                                <span class="ri-delete-bin-7-line"></span>
                                                            </a>
                                                        </div>

                                                    </div>
                                                </td>
                                            </tr>
                                        </tbody>
                                    @endforeach
                                </table>
                            </div>

                            <div class="row justify-content-end">
                                {{ $categories->appends(request()->query())->links() }}
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
