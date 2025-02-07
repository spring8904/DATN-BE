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
                            <img src="{{ $approval->course->thumbnail }}" alt=""
                                 class="rounded-circle img-fluid h-100 object-fit-cover">
                        </div>
                    </div>
                </div>
                <div class="col">
                    <div class="p-2">
                        <h3 class="text-white mb-1">
                            {{ $approval->course->name }}
                            @if($approval->status === 'pending')
                                <span class="badge badge-label bg-warning">
                                                            <i class="mdi mdi-circle-medium"></i> Chờ phê duyệt
                                                        </span>
                            @elseif($approval->status === 'approved')
                                <span class="badge badge-label bg-success"><i class="mdi mdi-circle-medium"></i> Đã duyệt</span>
                            @else
                                <span class="badge badge-label bg-danger"><i class="mdi mdi-circle-medium"></i>Đã từ chối</span>
                            @endif
                        </h3>
                        <div class="hstack gap-3 flex-wrap mt-3 text-white">
                            <div>
                                <i class="ri-map-pin-user-line me-1"></i>
                                Người hướng dẫn : {{ $approval->course->user->name ?? ''  }}
                            </div>
                            <div class="vr"></div>
                            <div>
                                <i class="ri-building-line align-bottom me-1"></i>
                                Danh mục : {{ $approval->course->category->name ?? ''  }}
                            </div>
                            <div class="vr"></div>
                            <div>Ngày tạo : <span
                                    class="fw-medium">{{ $approval->course->created_at ?? ''  }}</span>
                            </div>
                            <div class="vr"></div>
                        </div>
                    </div>
                </div>

                <div class="col-12 col-lg-auto order-last order-lg-0">
                    <button class="btn btn-success">Phê duyệt</button>
                    <button class="btn btn-danger">Từ chối</button>
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
                            <li class="nav-item">
                                <a class="nav-link fs-14" data-bs-toggle="tab" href="#test-case" role="tab">
                                    <i class="ri-folder-4-line d-inline-block d-md-none"></i> <span
                                        class="d-none d-md-inline-block">Điều kiện</span>
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
                                <p class="text-muted mb-4">{{ $approval->course->description }}</p>
                                <div>
                                    <h5 class="mb-3">Yêu cầu</h5>
                                    <ul class="text-muted vstack gap-2">
                                        @php
                                            $requirements = json_decode($approval->course->requirements, true);
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
                                            $benefits = json_decode($approval->course->benefits, true);
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
                                        $qa = json_decode($approval->course->qa, true);
                                    @endphp
                                    <div class="accordion" id="default-accordion-example">
                                        @foreach ($qa as $index => $item)
                                            <div class="accordion-item">
                                                <h2 class="accordion-header" id="heading{{ $index + 1 }}">
                                                    <button class="accordion-button {{ $index == 0 ? '' : 'collapsed' }}" type="button" data-bs-toggle="collapse" data-bs-target="#collapse{{ $index + 1 }}" aria-expanded="{{ $index == 0 ? 'true' : 'false' }}" aria-controls="collapse{{ $index + 1 }}">
                                                        {{ $item['question'] }}
                                                    </button>
                                                </h2>
                                                <div id="collapse{{ $index + 1 }}" class="accordion-collapse collapse {{ $index == 0 ? 'show' : '' }}" aria-labelledby="heading{{ $index + 1 }}" data-bs-parent="#default-accordion-example">
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
                               <div>
                                   <div style="width: 80px;" class="progress animated-progress custom-progress progress-label">
                                       <div class="progress-bar bg-danger" role="progressbar" style="width: 30%" aria-valuenow="30" aria-valuemin="0" aria-valuemax="100">
                                           <div class="label">30%</div>
                                       </div>
                                   </div>
                               </div>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive table-card">
                                    <table class="table mb-0">
                                        <tbody>
                                        <tr>
                                            <td class="fw-medium">Thời lượng</td>
                                            <td>Product Designer</td>
                                        </tr>
                                        <tr>
                                            <td class="fw-medium">Chương học</td>
                                            <td>Themesbrand</td>
                                        </tr>
                                        <tr>
                                            <td class="fw-medium">Bài học</td>
                                            <td>Zuweihir, UAE</td>
                                        </tr>
                                        <tr>
                                            <td class="fw-medium">Time</td>
                                            <td><span class="badge bg-success-subtle text-success">Full Time</span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="fw-medium">Trình độ</td>
                                            <td>{{ $approval->course->level ?? '' }}</td>
                                        </tr>
                                        <tr>
                                            <td class="fw-medium">Giá </td>
                                            <td>{{ number_format($approval->course->price)  ?? ''}}</td>
                                        </tr>
                                        </tbody>
                                    </table>

                                </div>

                            </div>
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
                                <!-- Accordions with Icons -->
                                <div class="accordion custom-accordionwithicon" id="accordionWithicon">
                                    <div class="accordion-item">
                                        <h2 class="accordion-header" id="accordionwithiconExample1">
                                            <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#accor_iconExamplecollapse1" aria-expanded="true" aria-controls="accor_iconExamplecollapse1">
                                                <i class="ri-global-line"></i> How Does Age Verification Work?
                                            </button>
                                        </h2>
                                        <div id="accor_iconExamplecollapse1" class="accordion-collapse collapse show" aria-labelledby="accordionwithiconExample1" data-bs-parent="#accordionWithicon">
                                            <div class="accordion-body">
                                                Increase or decrease the letter spacing depending on the situation and try, try again until it looks right, and each assumenda labore aes Homo nostrud organic, assumenda labore aesthetic magna elements, buttons, everything.
                                            </div>
                                        </div>
                                    </div>
                                    <div class="accordion-item">
                                        <h2 class="accordion-header" id="accordionwithiconExample2">
                                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#accor_iconExamplecollapse2" aria-expanded="false" aria-controls="accor_iconExamplecollapse2">
                                                <i class="ri-user-location-line"></i> How Does Link Tracking Work?
                                            </button>
                                        </h2>
                                        <div id="accor_iconExamplecollapse2" class="accordion-collapse collapse" aria-labelledby="accordionwithiconExample2" data-bs-parent="#accordionWithicon">
                                            <div class="accordion-body">
                                                Nullam quis ante. Etiam sit amet orci eget eros faucibus tincidunt. Duis leo. Sed fringilla mauris sit amet nibh. Donec sodales sagittis magna. Sed consequat, leo eget bibendum sodales, augue velit cursus nunc, quis gravida magna mi a libero. Fusce vulputate eleifend sapien.
                                            </div>
                                        </div>
                                    </div>
                                    <div class="accordion-item">
                                        <h2 class="accordion-header" id="accordionwithiconExample3">
                                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#accor_iconExamplecollapse3" aria-expanded="false" aria-controls="accor_iconExamplecollapse3">
                                                <i class="ri-pen-nib-line"></i> How Do I Set Up the Drip Feature?
                                            </button>
                                        </h2>
                                        <div id="accor_iconExamplecollapse3" class="accordion-collapse collapse" aria-labelledby="accordionwithiconExample3" data-bs-parent="#accordionWithicon">
                                            <div class="accordion-body">
                                                Cras ultricies mi eu turpis hendrerit fringilla. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia Curae; In ac dui quis mi consectetuer lacinia. Nam pretium turpis et arcu arcu tortor, suscipit eget, imperdiet nec, imperdiet iaculis aliquam ultrices mauris.
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="tab-pane" id="test-case" role="tabpanel"></div>
        </div>
    </div>
@endsection
