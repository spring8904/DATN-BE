<div class="listjs-table" id="customerList">
    <div class="table-responsive table-card mt-3 mb-1">
        <table class="table align-middle table-nowrap">
            <thead class="table-light">
                <tr>
                    <th>STT</th>
                    <th>Ảnh bìa</th>
                    <th>Tên khóa học</th>
                    <th>Danh mục</th>
                    <th>Giảng viên</th>
                    <th>Trạng thái</th>
                    <th>Hành động</th>
                </tr>
            </thead>
            <tbody class="list form-check-all">
                @foreach ($courses as $course)
                    <tr>
                        <td class="id">{{ $loop->index + 1 }}</td>
                        <td class="phone">
                            @if ($course->thumbnail)
                                <img src="{{ $course->thumbnail }}" alt=""
                                    width="100px">
                            @else
                                <p>Không có ảnh</p>
                            @endif
                        </td>
                        <td class="id">{{ $course->name }}</td>
                        <td class="id">{{ $course->category->name ?? '' }}</td>
                        <td class="id">{{ $course->user->name ?? '' }}</td>

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
                                        <span class="ri-eye-line"></span>
                                    </button>
                                </a>
                            </div>
                        </td>


                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    {{ $courses->appends(request()->query())->links() }}
</div>