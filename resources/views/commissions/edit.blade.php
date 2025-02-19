@extends('layouts.app')


@section('content')
    <div class="container-fluid">

        <!-- start page title -->
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                    <h4 class="mb-sm-0">{{ $title ?? '' }}</h4>

                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                            <li class="breadcrumb-item active"><a href="">{{ $subTitle ?? '' }}</a></li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>
        <!-- end page title -->

        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header align-items-center d-flex">
                        <h4 class="card-title mb-0 flex-grow-1">{{ $subTitle ?? '' }}</h4>
                    </div>

                    <form action="{{ route('admin.commissions.update', $commission->id) }}" method="post"
                        enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <div class="card-body">
                            <div class="live-preview">
                                <div class="row">
                                    <div class="col-xxl-12 col-md-12">
                                        <label for="difficulty_level" class="form-label">Cấp độ</label>
                                        <select class="form-select mb-3" name="difficulty_level">
                                            <option value="">Chọn cấp độ</option>
                                            <option value="easy"
                                                {{ old('difficulty_level', $commission->difficulty_level) == 'easy' ? 'selected' : '' }}>
                                                Dễ
                                            </option>
                                            <option value="medium"
                                                {{ old('difficulty_level', $commission->difficulty_level) == 'medium' ? 'selected' : '' }}>
                                                Trung bình
                                            </option>
                                            <option value="difficult"
                                                {{ old('difficulty_level', $commission->difficulty_level) == 'difficult' ? 'selected' : '' }}>
                                                Khó
                                            </option>
                                            <option value="very_difficult"
                                                {{ old('difficulty_level', $commission->difficulty_level) == 'very_difficult' ? 'selected' : '' }}>
                                                Rất khó
                                            </option>
                                        </select>
                                        @if ($errors->has('difficulty_level'))
                                            <span class="text-danger">{{ $errors->first('difficulty_level') }}</span>
                                        @endif
                                    </div>
                                </div>

                                <div class="row">
                                    <!-- Phần trăm của hệ thống -->
                                    <div class="col-md-6">
                                        <label for="system_percentage" class="form-label">Phần trăm của hệ thống</label>
                                        <select id="system_percentage" class="form-select mb-3" name="system_percentage">
                                            <option value="">Chọn phần trăm</option>
                                            @foreach ([10, 20, 30, 40, 50, 60, 70, 80, 90] as $percent)
                                                <option value="{{ $percent }}"
                                                    {{ old('system_percentage', $commission->system_percentage) == $percent ? 'selected' : '' }}>
                                                    {{ $percent }}%
                                                </option>
                                            @endforeach
                                        </select>
                                        @if ($errors->has('system_percentage'))
                                            <span class="text-danger">{{ $errors->first('system_percentage') }}</span>
                                        @endif
                                    </div>

                                    <!-- Phần trăm của người hướng dẫn -->
                                    <div class="col-md-6">
                                        <label for="instructor_percentage" class="form-label">Phần trăm của người hướng
                                            dẫn</label>
                                        <select id="instructor_percentage" class="form-select mb-3"
                                            name="instructor_percentage">
                                            <option value="">Chọn phần trăm</option>
                                            @foreach ([10, 20, 30, 40, 50, 60, 70, 80, 90] as $percent)
                                                <option value="{{ $percent }}"
                                                    {{ old('instructor_percentage', $commission->instructor_percentage) == $percent ? 'selected' : '' }}>
                                                    {{ $percent }}%
                                                </option>
                                            @endforeach
                                        </select>
                                        @if ($errors->has('instructor_percentage'))
                                            <span class="text-danger">{{ $errors->first('instructor_percentage') }}</span>
                                        @endif
                                    </div>
                                </div>

                                <div class="mt-3">
                                    <button type="submit" class="btn btn-primary waves-effect waves-light">Cập nhật
                                    </button>
                                    <button type="reset" class="btn btn-info">Nhập lại</button>
                                    <a href="{{ route('admin.commissions.index') }}"
                                        class="btn btn-dark waves-effect waves-light">Quay lại</a>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('page-scripts')
    <script>
        $('#system_percentage').on('change', function() {
            var systemPercentage = parseInt($(this).val());
            if (systemPercentage) {
                var instructorPercentage = 100 - systemPercentage;
                $('#instructor_percentage').val(instructorPercentage);
            }
        });

        $('#instructor_percentage').on('change', function() {
            var instructorPercentage = parseInt($(this).val());
            if (instructorPercentage) {
                var systemPercentage = 100 - instructorPercentage;
                $('#system_percentage').val(systemPercentage);
            }
        });
    </script>
@endpush
