<?php

namespace App\Http\Controllers\API\Instructor;

use App\Http\Controllers\Controller;
use App\Http\Requests\API\Auth\RegisterInstructorRequest;
use App\Models\Career;
use App\Models\Profile;
use App\Traits\LoggableTrait;
use F9Web\ApiResponseHelpers;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class RegisterController extends Controller
{
    use LoggableTrait, ApiResponseHelpers;

    public function register(RegisterInstructorRequest $request)
    {
        try {
            $user = Auth::user();

            if (!$user->hasRole('member')) {
                return $this->respondUnAuthenticated('Tài khoản không phù hợp để đăng ký làm giảng viên.');
            }

            DB::beginTransaction();

            $validated = $request->validated();

            $dataProfiles = $request->only(['phone', 'address', 'experience']);
            $dataProfiles['bio'] = json_encode($request->bio);
            $dataProfiles['user_id'] = $user->id;

            $profile = Profile::query()->create($dataProfiles);

            $education = Career::query()->create([
                'institution_name' => $user->name,
                'degree' => $request->degree,
                'major' => $request->major,
                'certificates' => json_encode($request->certificates),
                'qa_systems' => json_encode($request->qa_systems),
                'start_date' => now(env('APP_TIMEZONE')),
                'profile_id' => $profile->id,
            ]);

            $user->assignRole("instructor");

            DB::commit();

            return $this->respondCreated('Đăng ký giảng viên thành công');
        } catch (\Exception $e) {
            DB::rollBack();

            $this->logError($e, $request->all());

            return $this->respondServerError('Có lỗi xảy ra, vui lòng thử lại');
        }
    }
}
