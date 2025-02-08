<div class="live-preview">
    <div class="table-responsive">
        <table class="table table-striped table-nowrap align-middle mb-0">
            <thead>
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">Quyền</th>
                    <th scope="col">Mô tả</th>
                    <th scope="col">Ngày tạo</th>
                    <th scope="col">Thao tác</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($permissions as $guardName => $groupedPermissions)
                    <td class="fw-bold" colspan="5">Module {{ Str::ucfirst($guardName) }}</td>
                    @foreach ($groupedPermissions as $permission)
                        <tr>
                            <td class="fw-medium">{{ $loop->iteration }}</td>
                            <td>{{ $permission->name }}</td>
                            <td>{{ $permission->description }}</td>
                            <td>{{ $permission->created_at }}</td>
                            <td>
                                <a href="{{ route('admin.permissions.edit', $permission) }}">
                                    <button class="btn btn-sm btn-warning edit-item-btn">
                                        <span class="ri-edit-box-line"></span>
                                    </button></a>


                                <a href="{{ route('admin.permissions.destroy', $permission->id) }}"
                                    class="sweet-confirm btn btn-sm btn-danger ">
                                    <span class="ri-delete-bin-7-line"></span>
                                </a>
                            </td>
                        </tr>
                    @endforeach
                @endforeach

            </tbody>
        </table>
    </div>
    <div class="row justify-content-end mt-3">
        {{ $permissions->appends(request()->query())->links() }}
    </div>
</div>
