@extends('layouts.auth')

@section('title', 'Reset Password')
@section('content')
<div class="container">
    <div class="row">
        <div class="col-lg-12">
            <div class="card overflow-hidden m-0">
                <div class="row justify-content-center g-0">
                    <div class="col-lg-6">
                        <div class="p-lg-5 p-4 auth-one-bg h-100">
                            <div class="bg-overlay"></div>
                            <div class="position-relative h-100 d-flex flex-column">
                                <div class="mb-4">
                                    <a style="display: flex;align-items: center; gap: 10px" href="index.html"
                                        class="">
                                        <img src="{{ asset('assets/images/logo-container.png') }}" alt=""
                                            width="50" height="50">
                                        <span class="custom-text-logo">CourseHub</span>
                                    </a>
                                </div>
                                <div class="mt-auto">
                                    <div class="mb-3">
                                        <i class="ri-double-quotes-l display-4 text-success"></i>
                                    </div>

                                    <div id="qoutescarouselIndicators" class="carousel slide" data-bs-ride="carousel">
                                        <div class="carousel-indicators">
                                            <button type="button" data-bs-target="#qoutescarouselIndicators"
                                                data-bs-slide-to="0" class="active" aria-current="true"
                                                aria-label="Slide 1"></button>
                                            <button type="button" data-bs-target="#qoutescarouselIndicators"
                                                data-bs-slide-to="1" aria-label="Slide 2"></button>
                                            <button type="button" data-bs-target="#qoutescarouselIndicators"
                                                data-bs-slide-to="2" aria-label="Slide 3"></button>
                                        </div>
                                        <div class="carousel-inner text-center text-white-50 pb-5">
                                            <div class="carousel-item active">
                                                <p class="fs-15 fst-italic">" Great! Clean code, clean design, easy for
                                                    customization. Thanks very much! "</p>
                                            </div>
                                            <div class="carousel-item">
                                                <p class="fs-15 fst-italic">" The theme is really great with an amazing
                                                    customer support."</p>
                                            </div>
                                            <div class="carousel-item">
                                                <p class="fs-15 fst-italic">" Great! Clean code, clean design, easy for
                                                    customization. Thanks very much! "</p>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- end carousel -->

                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-6">
                        <table class="body-wrap" style="font-family: 'Roboto', sans-serif; box-sizing: border-box; font-size: 14px; width: 100%; background-color: transparent; margin: 0;">
                            <tr style="font-family: 'Roboto', sans-serif; box-sizing: border-box; font-size: 14px; margin: 0;">
                                <td style="font-family: 'Roboto', sans-serif; box-sizing: border-box; font-size: 14px; vertical-align: top; margin: 0;" valign="top"></td>
                                <td class="container" width="600" style="font-family: 'Roboto', sans-serif; box-sizing: border-box; font-size: 14px; vertical-align: top; display: block !important; max-width: 600px !important; clear: both !important; margin: 0 auto;" valign="top">
                                    <div class="content" style="font-family: 'Roboto', sans-serif; box-sizing: border-box; font-size: 14px; max-width: 600px; display: block; margin: 0 auto; padding: 20px;">
                                        <table class="main" width="100%" cellpadding="0" cellspacing="0" itemprop="action" itemscope itemtype="http://schema.org/ConfirmAction" style="font-family: 'Roboto', sans-serif; box-sizing: border-box; font-size: 14px; border-radius: 3px; margin: 0; border: none;">
                                            <tr style="font-family: 'Roboto', sans-serif; font-size: 14px; margin: 0;">
                                                <td class="content-wrap" style="font-family: 'Roboto', sans-serif; box-sizing: border-box; color: #495057; font-size: 14px; vertical-align: top; margin: 0;padding: 30px; box-shadow: 0 3px 15px rgba(30,32,37,.06); ;border-radius: 7px; background-color: #fff;" valign="top">
                                                    <meta itemprop="name" content="Confirm Email" style="font-family: 'Roboto', sans-serif; box-sizing: border-box; font-size: 14px; margin: 0;" />
                                                    <table width="100%" cellpadding="0" cellspacing="0" style="font-family: 'Roboto', sans-serif; box-sizing: border-box; font-size: 14px; margin: 0;">
                                                        <tr style="font-family: 'Roboto', sans-serif; box-sizing: border-box; font-size: 14px; margin: 0;">
                                                            <td class="content-block" style="font-family: 'Roboto', sans-serif; box-sizing: border-box; font-size: 14px; vertical-align: top; margin: 0; padding: 0 0 20px;" valign="top">
                                                                <div style="text-align: center;">
                                                                    <i data-feather="lock" style="color: #0ab39c;fill: rgba(10,179,156,.16); height: 30px; width: 30px;"></i>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                        <tr style="font-family: 'Roboto', sans-serif; box-sizing: border-box; font-size: 14px; margin: 0;">
                                                            <td class="content-block" style="font-family: 'Roboto', sans-serif; box-sizing: border-box; font-size: 24px; vertical-align: top; margin: 0; padding: 0 0 10px;  text-align: center;" valign="top">
                                                                <h4 style="font-family: 'Roboto', sans-serif; margin-bottom: 0px;font-weight: 500; line-height: 1.5;">Thay đổi hoặc cài lại mật khẩu của bạn</h5>
                                                            </td>
                                                        </tr>
                                                        <tr style="font-family: 'Roboto', sans-serif; box-sizing: border-box; font-size: 14px; margin: 0;">
                                                            <td class="content-block" style="font-family: 'Roboto', sans-serif; color: #878a99; box-sizing: border-box; font-size: 15px; vertical-align: top; margin: 0; padding: 0 0 12px; text-align: center;" valign="top">
                                                                <p style="margin-bottom: 13px; line-height: 1.5;">Bạn có thể thay đổi mật khẩu vì lý do bảo mật hoặc đặt lại mật khẩu nếu bạn quên. Mật khẩu Tài khoản Admin của bạn được sử dụng để truy cập trang quản trị.</p>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td>
                                                                <div class="mt-2 text-center">
                                                                    <lord-icon
                                                                        src="https://cdn.lordicon.com/rhvddzym.json" trigger="loop" colors="primary:#0ab39c" class="avatar-xl">
                                                                    </lord-icon>
                                                                </div>
                                                                <div class="p-2">
                                                                    <form>
                                                                        <div class="mb-4">
                                                                            <label class="form-label">Email</label>
                                                                            <input type="email" class="form-control" id="email" placeholder="Nhập địa chỉ gmail của bạn để lấy lại mật khẩu">
                                                                        </div>
                        
                                                                        <div class="text-center mt-4">
                                                                            <button class="btn btn-success w-100" type="submit">Gửi</button>
                                                                        </div>
                                                                    </form><!-- end form -->
                                                                </div>
                                                            </td>
                                                        </tr>              
                                                        <tr style="font-family: 'Roboto', sans-serif; box-sizing: border-box; font-size: 14px; margin: 0;">
                                                        </tr>
                                                    </table>
                                                </td>
                                            </tr>
                                        </table>
                                    </div>
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
            <!-- end card -->
        </div>
        <!-- end col -->

    </div>
    <!-- end row -->
</div>
@endsection
