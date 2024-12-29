@extends('layouts.app')

@section('title', $title)

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                    <h4 class="mb-sm-0">{{ $title ?? '' }}</h4>

                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                            <li class="breadcrumb-item active">{{ $title ?? '' }}</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>

        <form action="{{ route('admin.roles.update', $role->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header align-items-center d-flex">
                            <h4 class="card-title mb-0 flex-grow-1">{{ $subTitle ?? '' }}</h4>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-12">
                                    <label class="form-label">Tên vai trò</label>
                                    <input type="text" class="form-control mb-2" placeholder="Nhập tên vai trò..."
                                        value="{{ $role->name ?? '' }}" name="name">

                                    @if ($errors->has('name'))
                                        <span class="text-danger">{{ $errors->first('name') }}</span>
                                    @endif
                                </div>
                                <div class="col-md-12">
                                    <label for="inputEmail4" class="form-label">Mô tả</label>
                                    <textarea class="form-control mb-2" placeholder="Nhập mô tả..." name="description" id="">{{ $role->description ?? '' }}</textarea>

                                    @if ($errors->has('description'))
                                        <span class="text-danger">{{ $errors->first('description') }}</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header align-items-center d-flex align-content-between">
                            <h4 class="card-title mb-0 flex-grow-1">Vai trò này có các quyền sau:</h4>
                            <button type="button" id="checkAll" class="btn btn-primary mb-3">Chọn tất cả quyền trong hệ
                                thống</button>
                        </div>
                        <div class="card-body">
                            <div class="live-preview">
                                <div class="accordion custom-accordionwithicon accordion-secondary" id="accordionWithicon">
                                    @foreach ($permissions as $module => $modulePermissions)
                                        <div class="accordion-item">
                                            <h2 class="accordion-header"
                                                id="accordionWithiconExample{{ $loop->iteration }}">
                                                <button class="accordion-button" type="button" data-bs-toggle="collapse"
                                                    data-bs-target="#accor_iconExamplecollapse{{ $loop->iteration }}"
                                                    aria-expanded="{{ $loop->first ? 'true' : 'false' }}"
                                                    aria-controls="accor_iconExamplecollapse{{ $loop->iteration }}">
                                                    <i class="ri-global-line me-2"></i> Module
                                                    {{ Str::ucfirst($module) }}
                                                </button>
                                            </h2>
                                            <div id="accor_iconExamplecollapse{{ $loop->iteration }}"
                                                class="accordion-collapse collapse {{ $loop->first ? 'show' : '' }}"
                                                aria-labelledby="accordionWithiconExample{{ $loop->iteration }}"
                                                data-bs-parent="#accordionWithicon">
                                                <div class="accordion-body">
                                                    <button type="button" id="selectAll_{{ $loop->iteration }}"
                                                        class="btn btn-primary mb-3">Chọn tất cả quyền của Module
                                                        {{ Str::ucfirst($module) }}</button>
                                                    <div class="row">
                                                        @foreach ($modulePermissions as $permission)
                                                            <div class="col-md-3">
                                                                <div class="card">
                                                                    <div class="card-body">
                                                                        <div class="form-check">
                                                                            <input type="checkbox" class="form-check-input"
                                                                                name="permissions[]"
                                                                                value="{{ $permission->id }}"
                                                                                id="permission_{{ $permission->id }}"
                                                                                {{ in_array($permission->id, $role->permissions->pluck('id')->toArray()) ? 'checked' : '' }}>
                                                                            <label class="form-check-label"
                                                                                for="permission_{{ $permission->id }}">
                                                                                {{ $permission->name }}
                                                                            </label>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12">
                <div class="">
                    <button type="submit" class="btn btn-primary">Cập nhật</button>
                    <button type="reset" class="btn btn-secondary ms-2">Nhập lại</button>
                    <a class="btn btn-dark ms-2" href="{{ route('admin.roles.index') }}">Danh sách</a>
                </div>
            </div>
        </form>
    </div>
@endsection

@section('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const selectAllButtons = document.querySelectorAll('[id^="selectAll_"]');

            selectAllButtons.forEach(button => {
                button.addEventListener('click', () => {
                    const accordionBody = button.closest('.accordion-body');
                    const checkboxes = accordionBody.querySelectorAll('.form-check-input');

                    const allChecked = Array.from(checkboxes).every(checkbox => checkbox.checked);

                    checkboxes.forEach(checkbox => {
                        checkbox.checked = !allChecked;
                    });
                });
            });

            const checkAllButton = document.getElementById('checkAll');
            if (checkAllButton) {
                checkAllButton.addEventListener('click', () => {
                    const checkboxes = document.querySelectorAll('.form-check-input');
                    const allChecked = Array.from(checkboxes).every(checkbox => checkbox.checked);

                    checkboxes.forEach(checkbox => {
                        checkbox.checked = !allChecked;
                    });
                });
            }
        });
    </script>
@endsection
