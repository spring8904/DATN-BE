<?php

namespace App\Http\Controllers\API\Instructor;

use App\Http\Controllers\Controller;
use App\Http\Requests\API\SupportBank\StoreGenerateQrRequest;
use App\Models\SupportedBank;
use App\Traits\LoggableTrait;
use F9Web\ApiResponseHelpers;
use Illuminate\Http\Request;

class SupportBankController extends Controller
{
    use LoggableTrait, ApiResponseHelpers;

    public function index()
    {
        try {
            $banks = SupportedBank::query()->get();

            if ($banks->isEmpty()) {
                return $this->respondNotFound('Không tìm thấy ngân hàng nào');
            }

            return $this->respondOk('Danh sách ngân hàng: ', $banks);
        } catch (\Exception $e) {
            $this->logError($e);

            return $this->respondServerError(
                'Có lỗi xảy ra, vui lòng thử lại sau'
            );
        }
    }

    public function generateQR(StoreGenerateQrRequest $request)
    {
        try {
            $data = $request->validated();

            $response = \Illuminate\Support\Facades\Http::post('https://api.vietqr.io/v2/generate', $data);

            if ($response->failed()) {
                return $this->respondFailedValidation('Có lỗi xảy ra, vui lòng thử lại sau');
            }

            $imageData = base64_decode($response->json()['data']['qrDataURL']);

            return $this->respondCreated('Tạo mã QR thành công', $imageData);
        } catch (\Exception $e) {
            $this->logError($e, $request->all());

            return $this->respondServerError(
                'Có lỗi xảy ra, vui lòng thử lại sau'
            );
        }
    }
}
