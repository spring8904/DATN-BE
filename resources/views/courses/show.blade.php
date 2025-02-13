@extends('layouts.app')

@push('page-css')
    <link rel="stylesheet" href="{{ asset('vendor/laraberg/css/laraberg.css') }}">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
@endpush

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                    <h4 class="mb-sm-0">{{ $course->title }}</h4>
                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                            <li class="breadcrumb-item active">Chi tiết khóa học</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-8">
                <div class="card">
                    <div class="card-header align-items-center d-flex">
                        <h4 class="card-title mb-0 flex-grow-1">
                            Thông tin khóa học <span class="text-danger">{{ $course->name }}</span>
                        </h4>
                        
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="form-label">Hình đại diện:</label>
                            <img class="img-thumbnail" src="{{ $course->thumbnail }}" alt="Hình đại diện">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Mô tả bài viết:</label>
                            <textarea class="form-control" cols="30" rows="10">
                            {{ $course->intro }}
                        </textarea>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Nội dung:</label>
                            <textarea class="form-control" cols="30" rows="10">{{ $course->description }}</textarea>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="card">
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="form-label">Tiêu đề:</label>
                            <p class="text-muted">{{ $course->name }}</p>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Người tạo bài:</label>
                            <p class="text-muted">{{ $course->user->name }}</p>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Trạng thái:</label>
                            <p class="text-muted">{{ ucfirst($course->status) }}</p>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Mức độ:</label>
                            <p class="text-muted">{{ $course->level }}</p>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Ngày xuất bản:</label>
                            <p class="text-muted">
                                {{ $course->published_at ? $course->published_at->format('Y/m/d H:i') : 'Chưa xuất bản' }}</p>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Danh mục:</label>
                            <select class="select2-categories form-control" multiple="multiple" disabled>
                                <option selected>{{ $course->category->name }}</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Đường dẫn thân thiện:</label>
                            <p class="text-muted">{{ $course->slug }}</p>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Tổng số học viên:</label>
                            <p class="text-muted">{{ $course->total_student }} học viên</p>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Ngày tạo bài:</label>
                            <p class="text-muted">{{ $course->created_at->format('d/m/Y H:i') }}</p>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Ngày chỉnh sửa:</label>
                            <p class="text-muted">{{ $course->updated_at->format('d/m/Y H:i') }}</p>
                        </div>
                    </div>
                </div>
                <div class="text-end">
                    <a href="{{ route('admin.courses.index') }}" class="btn btn-warning">Quay lại danh sách</a>
                    
                </div>
            </div>
        </div>
    </div>
@endsection

@push('page-scripts')
    <script src="{{ asset('assets/libs/@ckeditor/ckeditor5-build-classic/build/ckeditor.js') }}"></script>

    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <script src="https://unpkg.com/react@17.0.2/umd/react.production.min.js"></script>
    <script src="https://unpkg.com/react-dom@17.0.2/umd/react-dom.production.min.js"></script>

    <script src="{{ asset('vendor/laraberg/js/laraberg.js') }}"></script>
    <script>
        $(document).ready(function() {
            Laraberg.init('laraberg');

            $('.select2-categories').select2({
                placeholder: 'Không có'
            });

            $('.select2-tags').select2({
                tags: true,
                tokenSeparators: [','],
                placeholder: 'Không có'
            });

            ClassicEditor.create($('#ckeditor-classic')[0])
                .then(editor => {
                    editor.ui.view.editable.element.style.height = "200px";
                })
                .catch(console.error);
        });
    </script>
@endpush
