<?php

namespace App\Http\Controllers\API\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\API\Users\SinginUserRequest;
use App\Http\Requests\API\Users\SingupUserRequest;
use App\Models\User;
use App\Traits\LoggableTrait;
use F9Web\ApiResponseHelpers;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    use LoggableTrait, ApiResponseHelpers;

    public function signUp(SingupUserRequest $request)
    {
        try {
            DB::beginTransaction();

            $validated = $request->validated();

            $data = $request->only(['name', 'email', 'password', 'repassword']);
            $data['avatar'] = 'https://res.cloudinary.com/dvrexlsgx/image/upload/v1732148083/Avatar-trang-den_apceuv_pgbce6.png';

            do {
                $data['code'] = str_replace('-', '', Str::uuid()->toString());
            } while (User::query()->where('code', $data['code'])->exists());

            $user = User::query()->create($data);

            $user->assignRole("member");

            DB::commit();

            return response()->json([
                'status' => true,
                'message' => 'Tạo tài khoản thành công, vui lòng đăng nhập',
            ], Response::HTTP_OK);
        } catch (\Exception $e) {
            DB::rollBack();

            $this->logError($e);

            return response()->json([
                'status' => false,
                'message' => 'Có lỗi xảy ra, vui lòng thử lại'
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function signIn(SinginUserRequest $request)
    {
        try {
            $validated = $request->validated();

            $data = $request->only(['email', 'password']);

            if (Auth::attempt($data)) {

                $user = Auth::user();

                if ($user->status == "blocked") {

                    Auth::logout();

                    return response()->json([
                        'status' => false,
                        'message' => 'Tài khoản đã bị cấm!',
                    ], Response::HTTP_FORBIDDEN);
                } else {

                    return response()->json([
                        'status' => true,
                        'message' => 'Đăng nhập thành công',
                        'user' => $user,
                        'token' => $user->createToken('API Token')->plainTextToken
                    ], Response::HTTP_OK);
                }
            } else {
                return response()->json([
                    'status' => false,
                    'message' => 'Tài khoản hoặc mật khẩu không đúng'
                ], Response::HTTP_UNAUTHORIZED);
            }
        } catch (\Exception $e) {
            $this->logError($e);

            return response()->json([
                'status' => false,
                'message' => 'Có lỗi xảy ra, vui lòng thử lại'
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
