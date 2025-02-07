@extends('layouts.app')


@section('content')
    <div class="container-fluid">

        <!-- start page title -->
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                    <h4 class="mb-sm-0">Listjs</h4>

                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="javascript: void(0);">Tables</a></li>
                            <li class="breadcrumb-item active">Listjs</li>
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
                        <h4 class="card-title mb-0 flex-grow-1">Chỉnh sửa hoa hồng</h4>
                        
                    </div>

                    <!-- end card header -->
                    <form action="{{ route('admin.commissions.update', $commission->id) }}" method="post" enctype="multipart/form-data">

                        @csrf
                        @method('PUT')


                        <div class="card-body">
                            <div class="live-preview">
                                <div class="row gy-4">
                                    <div class="col-xxl-6 col-md-6">
                                        <div>
                                            <label for="placeholderInput" class="form-label">Độ khó</label>
                                            <select class="form-select mb-3" aria-label="Default select example"
                                                name="difficulty_level">
                                                <option value="">Chọn độ khó </option>
                                                <option @if (isset($commission->difficulty_level) == 'easy')
                                                    selected
                                                @endif value="{{ $commission->difficulty_level }}">Dễ</option>

                                                <option @if (isset($commission->difficulty_level) == 'medium')
                                                    selected
                                                @endif value="{{ $commission->difficulty_level }}">Trung bình</option>

                                                <option @if (isset($commission->difficulty_level) == 'difficult')
                                                    selected
                                                @endif value="{{ $commission->difficulty_level }}">Khó</option>
                                              

                                            </select>
                                        </div>
                                        @if ($errors->has('name'))
                                            <span class="text-danger">{{ $errors->first('difficulty_level') }}</span>
                                        @endif
                                    </div>
                                    <!--end col-->
                                    <div class="col-xxl-6 col-md-6">
                                        <div>
                                            <label for="placeholderInput" class="form-label">Phần trăm của hệ thống</label>
                                            <select id="system_percentage" class="form-select mb-3" aria-label="Default select example"
                                                name="system_percentage">
                                                <option value="">Chọn phần trăm </option>

                                                <option @if (isset($commission->system_percentage) == 50)
                                                    selected
                                                @endif value="50">50%</option>

                                                <option @if (isset($commission->system_percentage) == 60)
                                                    selected
                                                @endif value="60">60%</option>

                                                <option @if (isset($commission->system_percentage) == 70)
                                                    selected
                                                @endif value="70">70%</option>
                                
                                            </select>
                                        </div>
                                        @if ($errors->has('name'))
                                            <span class="text-danger">{{ $errors->first('difficulty_level') }}</span>
                                        @endif
                                    </div>
                                    <!--end col-->

                                    <div class="col-xxl-6 col-md-6">
                                        <div>
                                            <label for="placeholderInput" class="form-label">Phần trăm của người hướng dẫn</label>
                                            <select id="instructor_percentage" class="form-select mb-3" aria-label="Default select example"
                                                name="instructor_percentage">
                                                <option value="">Chọn phần trăm </option>
                                                <option @if (isset($commission->instructor_percentage) == 50)
                                                    selected
                                                @endif value="50">50%</option>

                                                <option @if (isset($commission->instructor_percentage) == 40)
                                                    selected
                                                @endif value="40">40%</option>
                                                
                                                <option @if (isset($commission->instructor_percentage) == 30)
                                                    selected
                                                @endif value="30">30%</option>
                                                

                                            </select>
                                        </div>
                                        @if ($errors->has('name'))
                                            <span class="text-danger">{{ $errors->first('difficulty_level') }}</span>
                                        @endif
                                    </div>

                                    <!--end col-->
                                </div>

                                <div class="mt-3">
                                    <button type="submit" class="btn btn-danger waves-effect waves-light">Cập nhập</button>
                                    {{-- <a href="{{ route('admin.categories.index') }}" type="button" class="btn btn-danger waves-effect waves-lightm ">Quay lại</a> --}}
                                </div>
                            </div>
                        </div>
                    </form>

                </div>
            </div>
            <!--end col-->
        </div>
        <!-- end row -->





    </div>
@endsection
<div>
    <!-- People find pleasure in different ways. I find it in keeping my mind clear. - Marcus Aurelius -->
</div>

<div>
    <!-- Waste no more time arguing what a good man should be, be one. - Marcus Aurelius -->
</div>
