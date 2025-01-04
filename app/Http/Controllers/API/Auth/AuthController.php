<?php

namespace App\Http\Controllers\API\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\API\Users\SinginUserRequest;
use App\Http\Requests\API\Users\SingupUserRequest;
use App\Models\User;
use App\Traits\LoggableTrait;
use Carbon\Carbon;
use F9Web\ApiResponseHelpers;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

use function PHPUnit\Framework\isEmpty;

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
            ], Response::HTTP_CREATED);
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

            $user = User::query()->where('email', $data['email'])->first();

            if (is_null($user)) {
                return response()->json([
                    'status' => false,
                    'message' => 'Tài khoản không tồn tại, vui lòng thử lại!',
                    'user' => $user
                ], Response::HTTP_UNAUTHORIZED);
            }

            if ($user->status == "blocked") {
                return response()->json([
                    'status' => false,
                    'message' => 'Tài khoản đã bị khóa!',
                ], Response::HTTP_FORBIDDEN);
            }

            if (Auth::attempt($data)) {

                DB::beginTransaction();

                if ($user->status == "inactive") {
                    $user->status = "active";
                    $user->save();
                }

                $expiresAt = Carbon::now('Asia/Ho_Chi_Minh')->addMonth();

                $token = $user->createToken('API Token');

                $tokenInst = $token->accessToken;
                $tokenInst->expires_at = $expiresAt;
                $tokenInst->save();

                DB::commit();

                return response()->json([
                    'status' => true,
                    'message' => 'Đăng nhập thành công',
                    'user' => $user,
                    'token' => $token->plainTextToken,
                    'expires_at' => $expiresAt
                ], Response::HTTP_OK);
            } else {
                return response()->json([
                    'status' => false,
                    'message' => 'Mật khẩu không đúng'
                ], Response::HTTP_UNAUTHORIZED);
            }
        } catch (\Exception $e) {
            DB::rollBack();

            $this->logError($e);

            return response()->json([
                'status' => false,
                'message' => 'Có lỗi xảy ra, vui lòng thử lại'
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
