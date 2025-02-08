<div class="listjs-table" id="customerList">
    <div class="row g-4 mb-3">
        <div class="col-sm-auto">
            <div>
                <a href="{{ route('admin.categories.create') }}">
                    <button type="button" class="btn btn-primary add-btn">
                        <i class="ri-add-line align-bottom me-1"></i> Thêm mới
                    </button>
                </a>
                <button class="btn btn-danger" id="deleteSelected">
                    <i class="ri-delete-bin-2-line"> Xóa nhiều</i>
                </button>
            </div>
        </div>
        <div class="col-sm">
            <div class="d-flex justify-content-sm-end">
                <div class="search-box ms-2">
                    <input type="text" name="search_full" id="searchFull"
                           class="form-control search" placeholder="Tìm kiếm..." data-search
                           value="{{ request()->input('search_full') ?? '' }}">
                    <button id="search-full" class="ri-search-line search-icon m-0 p-0 border-0"
                            style="background: none;"></button>
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
                <th>STT</th>
                <th>Tên danh mục</th>
                <th>Cấp độ</th>
                <th>Trạng thái</th>
                <th>Ngày tạo</th>
                <th>Hành Động</th>
            </tr>
            </thead>
            <tbody class="list">
            @foreach($categories as $category)
                <tr>
                    <td>
                        <input type="checkbox" class="checkItem" value="{{ $category->id }}">
                    </td>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $category->name }}</td>
                    <td>
                        @if(is_null($category->parent_id))
                            <span class="badge bg-primary">Danh mục cha</span>
                        @else
                            <span class="badge bg-info">Danh mục con</span>
                        @endif
                    </td>
                    <td>
                        @if($category->status === 1)
                            <span class="badge bg-success ">Hoạt động</span>
                        @else
                            <span class="badge bg-danger">Không hoạt động</span>
                        @endif
                    </td>
                    <td>{{ $category->created_at }}</td>
                    <td>
                        <div class="d-flex gap-2">
                            <a href="{{ route('admin.categories.edit', $category->id) }}">
                                <button class="btn btn-sm btn-warning edit-item-btn">
                                    <span class="ri-edit-box-line"></span>
                                </button>
                            </a>
                            <a href="{{ route('admin.categories.destroy', $category->id) }}"
                               class="btn btn-sm btn-danger sweet-confirm">
                                <span class="ri-delete-bin-7-line"></span>
                            </a>
                        </div>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>

    <div class="row justify-content-end">
        {{ $categories->appends(request()->query())->links() }}
    </div>
</div>