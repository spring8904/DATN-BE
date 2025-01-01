<?php

namespace App\Http\Controllers\API\Auth;

use App\Http\Controllers\Controller;
use App\Models\SocialAccount;
use App\Models\User;
use App\Traits\LoggableTrait;
use F9Web\ApiResponseHelpers;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Laravel\Socialite\Facades\Socialite;

class GoogleController extends Controller
{
    use LoggableTrait, ApiResponseHelpers;

    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    public function handleGoogleCallback(Request $request)
    {
        try {
            $googleUser = Socialite::driver('google')->user();
            $user = null;

            DB::beginTransaction();

            $socialAccount =  SocialAccount::query()
                ->where([
                    'provider' => SocialAccount::PROVIDER_GOOGLE,
                    'provider_id' => $googleUser->getId()
                ])
                ->first();

            if ($socialAccount) {
                $user = $socialAccount->user;
            } else {
                $user = User::query()
                    ->create([
                        'name' => $googleUser->getName(),
                        'email' => $googleUser->getEmail(),
                        'avatar' => $googleUser->getAvatar(),
                        'password' => '',
                        'email_verified_at' => now(),
                    ]);

                $user->assignRole(User::ROLE_MEMBER);

                SocialAccount::query()->create([
                    'user_id' => $user->id,
                    'provider' => SocialAccount::PROVIDER_GOOGLE,
                    'provider_id' => $googleUser->getId(),
                    'avatar' => $googleUser->getAvatar(),
                ]);
            }

            DB::commit();

            Auth::login($user);

            return $this->respondOk('Đăng nhập thành công', [
                'user' => $user,
                'token' => $user->createToken('API Token')->plainTextToken
            ]);
        } catch (\Exception $e) {
            DB::rollBack();

            $this->logError($e, $request->all());

            return $this->respondServerError('Có lỗi xảy ra, vui lòng thử lại', Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
