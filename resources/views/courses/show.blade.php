@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        <div class="profile-foreground position-relative mx-n4 mt-n4">
            <div class="profile-wid-bg">
                <img src="{{ asset('assets/images/profile-bg.jpg') }}" alt="" class="profile-wid-img"/>
            </div>
        </div>
        <div class="pt-4 mb-4 mb-lg-3 pb-lg-4 profile-wrapper">
            <div class="row g-4">
                <div class="col-md-auto">
                    <div class="avatar-md">
                        <div class="avatar-title bg-white rounded-circle">
                            <img src="{{$course->thumbnail }}" alt=""
                                 class="rounded-circle img-fluid h-100 object-fit-cover">
                        </div>
                    </div>
                </div>
                <div class="col">
                    <div class="p-2">
                        <h3 class="text-white mb-1">
                            {{ $course->name }}
                        </h3>
                        <div class="hstack gap-3 flex-wrap mt-3 text-white">
                            <div>
                                <i class="ri-map-pin-user-line me-1"></i>
                                Người hướng dẫn : {{ $course->user->name ?? ''  }}
                            </div>
                            <div class="vr"></div>
                            <div>
                                <i class="ri-building-line align-bottom me-1"></i>
                                Danh mục : {{ $course->category->name ?? ''  }}
                            </div>
                            <div class="vr"></div>
                            <div>Ngày tạo : <span
                                        class="fw-medium">{{ $course->created_at ?? ''  }}</span>
                            </div>
                            <div class="vr"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12">
                <div>
                    <div class="d-flex profile-wrapper">
                        <ul class="nav nav-pills animation-nav profile-nav gap-2 gap-lg-3 flex-grow-1" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link fs-14 active" data-bs-toggle="tab" href="#overview-tab" role="tab">
                                    <i class="ri-airplay-fill d-inline-block d-md-none"></i> <span
                                            class="d-none d-md-inline-block">Tổng quan</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link fs-14" data-bs-toggle="tab" href="#curriculum" role="tab">
                                    <i class="ri-price-tag-line d-inline-block d-md-none"></i> <span
                                            class="d-none d-md-inline-block">Chương trình giảng dạy</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        <div class="tab-content pt-4 text-muted">
            <div class="tab-pane active" id="overview-tab" role="tabpanel">
                <div class="row">
                    <div class="col-xxl-9">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="mb-3">Mô tả</h5>
                                <p class="text-muted mb-4">{{ $course->description }}</p>
                                <div>
                                    <h5 class="mb-3">Yêu cầu</h5>
                                    <ul class="text-muted vstack gap-2">
                                        @php
                                            $requirements =  json_decode($course->requirements, true);
                                        @endphp
                                        @foreach($requirements as $requirement)
                                            <li>
                                                {{ $requirement }}
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                                <div>
                                    <h5 class="mb-3">Lợi ích</h5>
                                    <ul class="text-muted vstack gap-2">
                                        @php
                                            $benefits = json_decode($course->benefits, true);
                                        @endphp
                                        @foreach($benefits as $benefit)
                                            <li>
                                                {{ $benefit }}
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>

                                <div>
                                    <h5 class="mb-3">Câu hỏi thường gặp</h5>
                                    @php
                                        $qa = json_decode($course->qa, true);
                                    @endphp
                                    <div class="accordion" id="default-accordion-example">
                                        @foreach ($qa as $index => $item)
                                            <div class="accordion-item">
                                                <h2 class="accordion-header" id="heading{{ $index + 1 }}">
                                                    <button
                                                            class="accordion-button {{ $index == 0 ? '' : 'collapsed' }}"
                                                            type="button" data-bs-toggle="collapse"
                                                            data-bs-target="#collapse{{ $index + 1 }}"
                                                            aria-expanded="{{ $index == 0 ? 'true' : 'false' }}"
                                                            aria-controls="collapse{{ $index + 1 }}">
                                                        {{ $item['question'] }}
                                                    </button>
                                                </h2>
                                                <div id="collapse{{ $index + 1 }}"
                                                     class="accordion-collapse collapse {{ $index == 0 ? 'show' : '' }}"
                                                     aria-labelledby="heading{{ $index + 1 }}"
                                                     data-bs-parent="#default-accordion-example">
                                                    <div class="accordion-body">
                                                        {{ $item['answers'] }}
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xxl-3">
                        <div class="card">
                            <div class="card-header d-flex justify-content-between align-content-center">
                                <h5 class="mb-0">
                                    Tổng quan khoá học
                                </h5>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive table-card">
                                    <table class="table mb-0">
                                        <tbody>
                                        <tr>
                                            <td class="fw-medium">Chương học</td>
                                            <td>
                                                {{ $course->chapters->count() ?? '' }}
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="fw-medium">Bài học</td>
                                            <td>
                                                {{$course->chapters()->with('lessons')->count() ?? ''}}
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="fw-medium">Trình độ</td>
                                            <td>{{ $course->level ?? '' }}</td>
                                        </tr>
                                        @if($course->is_free == 1)
                                            <tr>
                                                <td class="fw-medium">Miễn phí</td>
                                                <td><span class="badge bg-success-subtle text-success">Có</span>
                                                </td>
                                            </tr>
                                        @else
                                            <tr>
                                                <td class="fw-medium">Giá</td>
                                                <td>{{ number_format($course->price)  ?? ''}}</td>
                                            </tr>

                                        @endif
                                        <tr>
                                            <td class="fw-medium">Ngày chấp nhận</td>
                                            <td>{!!  $course->accepted ?? '<span class="badge bg-success-subtle text-danger">Chưa kiểm duyệt</span>' !!}</td>
                                        </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <div class="text-end">
                            <a class="btn btn-primary" href="{{ route('admin.courses.index') }}">Quay lại danh sách</a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="tab-pane " id="curriculum" role="tabpanel">
                <div class="row">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="mb-0">Danh sách chương trình giảng dạy</h5>
                            </div>
                            <div class="card-body">
                                <div class="accordion" id="accordionWithicon">
                                    @foreach($course->chapters as $chapterIndex => $chapter)
                                        <div class="accordion-item">
                                            <h2 class="accordion-header" id="headingChapter{{ $chapterIndex }}">
                                                <button
                                                        class="accordion-button {{ $chapterIndex == 0 ? '' : 'collapsed' }}"
                                                        type="button"
                                                        data-bs-toggle="collapse"
                                                        data-bs-target="#collapseChapter{{ $chapterIndex }}"
                                                        aria-expanded="{{ $chapterIndex == 0 ? 'true' : 'false' }}"
                                                        aria-controls="collapseChapter{{ $chapterIndex }}">
                                                    <span
                                                            class="fw-bold">Chương {{ $chapterIndex + 1 }}</span>: {{ $chapter->title }}
                                                </button>
                                            </h2>
                                            <div id="collapseChapter{{ $chapterIndex }}"
                                                 class="accordion-collapse collapse {{ $chapterIndex == 0 ? 'show' : '' }}"
                                                 aria-labelledby="headingChapter{{ $chapterIndex }}"
                                                 data-bs-parent="#accordionWithicon">
                                                <div class="accordion-body">
                                                    <div class="accordion" id="accordionLessons{{ $chapterIndex }}">
                                                        @foreach($chapter->lessons->sortBy('order') as $lessonIndex => $lesson)
                                                            <div class="accordion-item">
                                                                <h2 class="accordion-header"
                                                                    id="headingLesson{{ $chapterIndex }}{{ $lessonIndex }}">
                                                                    <button
                                                                            class="accordion-button {{ $lessonIndex == 0 ? '' : 'collapsed' }}"
                                                                            type="button"
                                                                            data-bs-toggle="collapse"
                                                                            data-bs-target="#collapseLesson{{ $chapterIndex }}{{ $lessonIndex }}"
                                                                            aria-expanded="{{ $lessonIndex == 0 ? 'true' : 'false' }}"
                                                                            aria-controls="collapseLesson{{ $chapterIndex }}{{ $lessonIndex }}">
                                                                        <div
                                                                                class="d-flex w-100 justify-content-between align-items-center">
                                                                                <span
                                                                                        class="fw-bold d-flex align-items-center">
                                                                                    @if ($lesson->type === 'video')
                                                                                        <i class="ri-video-line me-2"></i>
                                                                                    @elseif($lesson->type === 'document')
                                                                                        <i class="ri-file-text-line me-2"></i>
                                                                                    @elseif($lesson->type === 'coding')
                                                                                        <i class="ri-code-s-slash-fill me-2"></i>
                                                                                    @elseif($lesson->type === 'quiz')
                                                                                        <i class="ri-questionnaire-fill me-2"></i>
                                                                                    @endif
                                                                                    Bài học {{ $lessonIndex + 1 }}: {{ $lesson->title }}
                                                                                </span>
                                                                            @if($lesson->type === 'video')
                                                                                <span class="ms-3">10h</span>
                                                                            @endif
                                                                        </div>
                                                                    </button>
                                                                </h2>
                                                                <div
                                                                        id="collapseLesson{{ $chapterIndex }}{{ $lessonIndex }}"
                                                                        class="accordion-collapse collapse {{ $lessonIndex == 0 ? 'show' : '' }}"
                                                                        aria-labelledby="headingLesson{{ $chapterIndex }}{{ $lessonIndex }}">
                                                                    <div class="accordion-body">
                                                                        @if($lesson->type === 'video')
                                                                            <mux-player
                                                                                    playback-id="EcHgOK9coz5K4rjSwOkoE7Y7O01201YMIC200RI6lNxnhs"
                                                                                    metadata-video-title="Test VOD"
                                                                                    metadata-viewer-user-id="user-id-007"
                                                                                    style="height: 300px; width: 100%;"
                                                                            ></mux-player>
                                                                        @endif
                                                                        <div
                                                                                style="margin-top: auto; display: flex; justify-content: flex-end;">
                                                                            <a class="btn btn-primary" href="#">Xem bài
                                                                                học</a>
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
        </div>
    </div>
@endsection

