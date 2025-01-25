<?php

namespace App\Http\Controllers\API\AI;

use App\Http\Controllers\Controller;
use App\Services\AiGeminiService;
use App\Traits\LoggableTrait;
use F9Web\ApiResponseHelpers;
use Illuminate\Http\Request;

class AiController extends Controller
{
    use LoggableTrait, ApiResponseHelpers;

    protected $aiService;

    public function __construct(AiGeminiService $aiService)
    {
        $this->aiService = $aiService;
    }

    public function generateText(Request $request)
    {
        try {
            $request->validate([
                'title' => 'required|string',
            ]);

            $response = $this->aiService->generateText($request->title);

            return $this->respondOk('Lấy dữ liệu thành công', $response);
        } catch (\Exception $e) {
            $this->logError($e, $request->all());

            return $this->respondServerError('Có lỗi xảy ra, vui lòng thử lại sau');
        }
    }
}
