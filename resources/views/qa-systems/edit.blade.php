@extends('layouts.app')

@section('title', 'Chỉnh sửa câu hỏi')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                    <h4 class="mb-sm-0">{{ $title ?? 'Chỉnh sửa câu hỏi' }}</h4>
                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                            <li class="breadcrumb-item active">{{ $title ?? 'Chỉnh sửa câu hỏi' }}</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header align-items-center d-flex">
                        <h4 class="card-title mb-0 flex-grow-1">{{ $subTitle ?? '' }}</h4>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('admin.qa-systems.update', $qaSystem->id) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group mb-3">
                                        <label class="form-label" for="title">Tiêu đề</label>
                                        <input type="text" class="form-control" id="title" name="title"
                                               value="{{ old('title', $qaSystem->title) }}" placeholder="Nhập tiêu đề">
                                        @error('title')
                                        <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="form-group mb-3">
                                        <label class="form-label" for="description">Mô tả</label>
                                        <textarea class="form-control" name="description" placeholder="Nhập mô tả">{{ old('description', $qaSystem->description) }}</textarea>
                                        @error('description')
                                        <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="form-group mb-3">
                                        <label class="form-label" for="question">Câu hỏi</label>
                                        <input type="text" class="form-control" id="question" name="question"
                                               value="{{ old('question', $qaSystem->question) }}" placeholder="Nhập câu hỏi">
                                        @error('question')
                                        <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="form-group mb-3">
                                        <div class="d-flex align-items-center justify-content-between">
                                            <label class="form-label">Lựa chọn</label>
                                            <button type="button" class="btn btn-sm btn-primary" id="add-option">Thêm lựa chọn</button>
                                        </div>
                                        <div id="options-container">
                                            @php
                                                $options = json_decode($qaSystem->options, true) ?? [];
                                            @endphp
                                            @foreach($options as $index => $option)
                                                <div class="input-group mb-3 option-item">
                                                    <span class="input-group-text">{{ $index + 1 }}</span>
                                                    <input type="text" class="form-control" name="options[]" value="{{ $option }}" placeholder="Nhập lựa chọn">
                                                    <button type="button" class="btn btn-danger remove-option">Xóa</button>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                    <div class="form-group mb-3">
                                        <label class="form-label">Loại câu hỏi</label>
                                        <select name="answer_type" class="form-control">
                                            <option value="multiple" {{ old('answer_type', $qaSystem->answer_type) == 'multiple' ? 'selected' : '' }}>Chọn nhiều</option>
                                            <option value="single" {{ old('answer_type', $qaSystem->answer_type) == 'single' ? 'selected' : '' }}>Chọn một</option>
                                        </select>
                                        @error('answer_type')
                                        <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="form-group mb-3">
                                        <label class="form-label">Trạng thái</label>
                                        <select name="status" class="form-control">
                                            <option value="1" {{ old('status', $qaSystem->status) == '1' ? 'selected' : '' }}>Kích hoạt</option>
                                            <option value="0" {{ old('status', $qaSystem->status) == '0' ? 'selected' : '' }}>Không kích hoạt</option>
                                        </select>
                                        @error('status')
                                        <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="mt-3">
                                        <button type="submit" class="btn btn-primary">Cập nhật</button>
                                        <a href="{{ route('admin.qa-systems.index') }}" class="btn btn-dark">Quay lại</a>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endSection

@push('page-scripts')
    <script>
        $(document).ready(function () {
            let optionCount = $('#options-container .option-item').length;

            $('#add-option').click(function () {
                optionCount++;
                let optionHtml = `
                <div class="input-group mb-2 option-item">
                    <span class="input-group-text">${optionCount}</span>
                    <input type="text" class="form-control" name="options[]" placeholder="Nhập lựa chọn">
                    <button type="button" class="btn btn-danger remove-option">Xóa</button>
                </div>`;
                $('#options-container').append(optionHtml);
            });

            $(document).on('click', '.remove-option', function () {
                $(this).closest('.option-item').remove();
                optionCount--;
                updateOptionNumbers();
            });

            function updateOptionNumbers() {
                $('#options-container .option-item').each(function (index) {
                    $(this).find('.input-group-text').text(index + 1);
                });
            }
        });
    </script>
@endpush
