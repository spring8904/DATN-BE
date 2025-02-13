<div class="listjs-table" id="customerList">
    <div class="row g-4 mb-3">
        
        <div class="col-sm">
            <div class="d-flex justify-content-sm-end">
                <div class="search-box ms-2">
                    <form action="{{ route('admin.courses.index') }}" method="get">
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
                    <th>Mã Khóa học</th>
                    <th>Tên khóa học</th>
                    <th>Ảnh bìa</th>
                    <th>Danh mục</th>
                    <th>Giảng viên</th>
                    <th>Giá</th>
                    <th>Giảm giá</th>
                    <th>Mức độ</th>
                    <th>Trạng thái</th>
                    <th>Hành động</th>
                    
                </tr>
            </thead>
            <tbody class="list form-check-all">
                @foreach ($courses as $course)
                    <tr>
                        <th scope="row">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="itemID"
                                    value="{{ $course->id }}">
                            </div>
                        </th>

                        <td class="id">{{ $course->code }}</td>
                        <td class="id">{{ $course->name }}</td>
                        <td class="phone">
                            @if ($course->thumbnail)
                                <img src="{{ $course->thumbnail }}" alt=""
                                    width="100px">
                            @else
                                <p>Không có ảnh</p>
                            @endif
                        </td>
                        <td class="id">{{ $course->category->name }}</td>
                        <td class="id">{{ $course->user->name }}</td>

                        <td class="date">{{ $course->price }}</td>
                        <td class="date">{{ $course->price_sale }}</td>
                        <td class="date">{{ $course->level }}</td>


                        @if ($course->status == 'approved')
                            <td class="status"><span class="badge bg-success-subtle text-success">
                                    Được phê duyệt
                                </span></td>
                        @elseif ($course->status == 'rejected')
                            <td class="status"><span class="badge bg-danger-subtle text-danger">
                                    Bị từ chối
                                </span></td>
                        @elseif ($course->status == 'pending')
                            <td class="status"><span class="badge bg-warning-subtle text-warning">
                                    Đang chờ duyệt
                                </span></td>
                        @else
                            <td class="status"><span class="badge bg-danger-subtle text-danger">
                                    Bản nháp
                                </span></td>
                        @endif
                
                        <td>
                            <div class="edit">
                                <a href="{{ route('admin.courses.show', $course->id) }}">
                                    <button class="btn btn-sm btn-info edit-item-btn">
                                        <span class="ri-folder-user-line"></span>
                                    </button>
                                </a>
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

    {{ $courses->appends(request()->query())->links() }}
</div>