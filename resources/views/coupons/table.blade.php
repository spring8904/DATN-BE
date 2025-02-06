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
                <th>Người tạo</th>
                <th>Tên mã giảm giá</th>
                <th>Mã giảm giá</th>
                <th>Giảm giá</th>
                <th>Trạng Thái</th>
                <th>Ngày bắt đầu</th>
                <th>Ngày kết thúc</th>
                <th>Số lượng sử dụng</th>
                <th>Hành động</th>
            </tr>
            </thead>
            <tbody class="list form-check-all">
            @foreach ($coupons as $coupon)
                <tr>
                    <th scope="row">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="itemID"
                                value="{{ $coupon->id }}">
                        </div>
                    </th>
                    <td class="id">{{ $coupon->id }}</td>
                    <td class="user_id">{{ $coupon->user_id }}</td>
                    <td class="name">{{ $coupon->name }}</td>
                    <td class="code">{{ $coupon->code }}</td>
                    <td class="discount_value">{{ $coupon->discount_value }} ({{$coupon->discount_type}}
                        )
                    </td>
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

                    <td class="start_date">{{ $coupon->start_date }}</td>
                    <td class="expire_date">{{ $coupon->expire_date }}</td>
                    <td class="used_count">{{ $coupon->used_count }}</td>
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
                                <a href="{{ route('admin.coupons.destroy', $coupon->id) }}"
                                   class="btn btn-sm btn-danger sweet-confirm">Xoá</a>
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
                <p class="text-muted mb-0">We've searched more than 150+ Orders We did not find
                    any
                    orders for you search.</p>
            </div>
        </div>
    </div>

    {{ $coupons->links() }}
</div>