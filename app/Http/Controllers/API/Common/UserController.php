<?php

namespace App\Http\Controllers\API\Common;

use App\Http\Controllers\Controller;
use App\Traits\LoggableTrait;
use F9Web\ApiResponseHelpers;
use Illuminate\Http\Request;

class UserController extends Controller
{
    use LoggableTrait, ApiResponseHelpers;

    public function showProfile()
    {
        try {

        } catch (\Exception $e) {
            $this->logError($e);

            return $this->respondServerError('Có lỗi xảy ra, vui lòng thử lại.');
        }
    }

    public function updateProfile(Request $request)
    {
        try {

        } catch (\Exception $e) {
            $this->logError($e, $request->all());

            return $this->respondServerError('Có lỗi xảy ra, vui lòng thử lại.');
        }
    }

    public function changePassword(Request $request)
    {
        try {

        } catch (\Exception $e) {
            $this->logError($e, $request->all());

            return $this->respondServerError('Có lỗi xảy ra, vui lòng thử lại.');
        }
    }

    public function getMyCourseBought(Request $request)
    {
        try {

        } catch (\Exception $e) {
            $this->logError($e, $request->all());

            return $this->respondServerError('Có lỗi xảy ra, vui lòng thử lại.');
        }
    }
}
