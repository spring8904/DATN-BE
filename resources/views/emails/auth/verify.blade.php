<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Xác Minh Email</title>
    <link href="{{ asset('assets/css/bootstrap.min.css') }}" rel="stylesheet" type="text/css" />
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f9f9f9;
            margin: 0;
            padding: 0;
        }
        .email-wrapper {
            width: 100%;
            background-color: #f9f9f9;
            padding: 30px 0;
        }
        .email-container {
            width: 600px;
            background-color: #ffffff;
            margin: 0 auto;
            padding: 40px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        .email-header {
            text-align: center;
            margin-bottom: 20px;
        }
        .email-header h1 {
            color: #333;
            font-size: 24px;
        }
        .email-body {
            color: #555;
            font-size: 16px;
            line-height: 1.5;
            margin-bottom: 20px;
        }
        .email-footer {
            text-align: center;
            font-size: 14px;
            color: #aaa;
        }
        .verify-btn {
            display: inline-block;
            background-color: #28a745;
            color: #fff;
            padding: 12px 24px;
            font-size: 16px;
            text-decoration: none;
            border-radius: 5px;
            text-align: center;
            margin: 20px 0;
        }
        .verify-btn:hover {
            background-color: #218838;
        }
    </style>
</head>
<body>
    <div class="email-wrapper">
        <div class="email-container">
            <!-- Header -->
            <div class="email-header">
                <div class="position-relative h-100 d-flex flex-column">
                    <div class="mb-4" style="margin-left: 150px">
                            <img src="../assets/images/logo-container.png" alt=""
                                width="50" height="50">
                            <h1 class="custom-text-logo text-lg">CourseMely</h1>
                    </div>
                </div>
                <h1>Xin Chào!</h1>
            </div>
            
            <!-- Body -->
            <div class="email-body">
                <p>Cảm ơn bạn đã đăng ký tài khoản tại CourseMeLy. Vui lòng xác minh địa chỉ email của bạn bằng cách nhấn vào nút dưới đây.</p>
                <a href="{{$verificationUrl}}" class="verify-btn">Xác Minh Email</a>
                <p>Nếu bạn không yêu cầu đăng ký tài khoản, vui lòng bỏ qua email này.</p>
            </div>

            <!-- Footer -->
            <div class="email-footer">
                <p>&copy; 2025 CourseMeLy. Mọi quyền được bảo lưu.</p>
            </div>
        </div>
    </div>
</body>
</html>
