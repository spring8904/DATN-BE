<?php

namespace App\Http\Controllers\API\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\API\Auth\ForgotPassWordRequest;
use App\Http\Requests\API\Auth\ResetPasswordRequest;
use App\Http\Requests\API\Auth\VerifyEmailRequest;
use App\Models\User;
use App\Traits\LoggableTrait;
use F9Web\ApiResponseHelpers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Spatie\Permission\Models\Role;

class AuthController extends Controller
{
    use LoggableTrait, ApiResponseHelpers;

    public function forgotPassword(ForgotPassWordRequest $forgotPassWordRequest)
    {
        try {
            //code...
            $status = Password::sendResetLink($forgotPassWordRequest->only('email'));

            if($status === Password::RESET_LINK_SENT)
            {
                return response()->json([
                    'success' => true,
                    'message' => __($status)
                ],200);
            }

        } catch (\Throwable $th) {
            //throw $th;

            return response()->json([
                'message' => 'Không thể gửi liên kết đặt lại mật khẩu. Vui lòng thử lại.',
            ], 400);
        }
    }

    public function resetPassword(ResetPasswordRequest $resetPasswordRequest)
    {
        try {
            //code...
            $data = $resetPasswordRequest->only('email', 'password', 'password_confirmation', 'token');

            $status = Password::reset(
                $data, 
                function($user, $password) {
                $user->forceFill([
                    'password' => Hash::make($password),
                ])->save();

                $user->token()->delete(); // Hủy token API cũ nếu dùng Sanctum
            });

            if($status === Password::PASSWORD_RESET)
            {
                return response()->json([
                    'success' => true,
                    'message' => __($status),
                ], 200);
            }
            
        } catch (\Throwable $th) {
            //throw $th;

            return response()->json([
                'success' => false,
                'message' => __($status),
            ], 400);
        }
    }

    public function verifyEmail(VerifyEmailRequest $verifyEmailRequest)
    {
        try {
            //code...
            $user = User::where('email', $verifyEmailRequest->email)->first();

            if ($user || Hash::check($verifyEmailRequest->password, $user->password)) {
                return response()->json([
                    'success' => true,
                    'message' => 'Mật khẩu chính xác.',
                ], 200);
            }

        } catch (\Throwable $th) {
            //throw $th;

            return response()->json([
                'success' => false,
                'message' => 'Mật khẩu không chính xác.',
            ], 400);
        }
    }
}
