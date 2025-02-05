<div class="listjs-table" id="customerList">
    <div class="row g-4 mb-3">
        <div class="col-sm-auto">
            <div>
                <a href="{{ route('admin.banners.create') }}" class="btn btn-success add-btn"><i
                        class="ri-add-line align-bottom me-1"></i> Thêm mới</a>
                        <button class="btn btn-danger" id="deleteSelected">
                            <i class="ri-delete-bin-2-line"> Xóa nhiều</i>
                        </button>
            </div>
        </div>
        <div class="col-sm">
            <div class="d-flex justify-content-sm-end">
                <div class="search-box ms-2">
                    <form action="{{ route('admin.banners.index') }}" method="get">
                        <input type="text" name="query" class="form-control search"
                            placeholder="Search..." value="{{ old('query') }}">
                        <i class="ri-search-line search-icon"></i>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="table-responsive table-card mt-3 mb-1">
        <table class="table align-middle table-nowrap" id="customerTable">
            <thead class="table-light">
                <tr>
                    <th scope="col" style="width: 50px;">
                        <input type="checkbox" id="checkAll">
                    </th>
                    <th>ID</th>
                    <th>Title</th>
                    <th>Image</th>
                    <th>Order</th>
                    <th>Status</th>
                    <th>Created_at</th>
                    <th>Updated_at</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody class="list form-check-all">
                @foreach ($banners as $banner)
                    <tr>
                        <th scope="row">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="itemID"
                                    value="{{ $banner->id }}">
                            </div>
                        </th>

                        <td class="id">{{ $banner->id }}</td>
                        <td class="title">{{ $banner->title }}</td>
                        <td class="image">
                            @if ($banner->image)
                                <img src="{{ $banner->image }}" alt="" width="100px">
                            @else
                                <p>No photo</p>
                            @endif

                        </td>
                        <td class="order">{{ $banner->order }}</td>
                        @if ($banner->status)
                            <td class="status"><span
                                    class="badge bg-success-subtle text-success">
                                    Active
                                </span></td>
                        @else
                            <td class="status"><span
                                    class="badge bg-danger-subtle text-danger">
                                    InActive
                                </span></td>
                        @endif

                        <td class="created_at">{{ $banner->created_at }}</td>
                        <td class="updated_at">{{ $banner->updated_at }}</td>
                        <td>
                            <div class="d-flex gap-2">
                                <div class="remove">
                                    <a href="{{ route('admin.banners.show', $banner->id) }}"
                                        class="btn btn-sm btn-primary remove-item-btn">Chi tiết</a>
                                </div>
                                <div class="edit">
                                    <a href="{{ route('admin.banners.edit', $banner->id) }}"
                                        class="btn btn-sm btn-success edit-item-btn">Sửa</a>
                                </div>
                                <div class="remove">
                                    <a href="{{ route('admin.banners.destroy', $banner->id) }}" class="btn btn-sm btn-danger sweet-confirm">Xoá</a>
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

    {{ $banners->links() }}
</div>