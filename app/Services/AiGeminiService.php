<?php

namespace  App\Services;

use App\Traits\LoggableTrait;
use F9Web\ApiResponseHelpers;
use Gemini;

class AiGeminiService {

    use LoggableTrait, ApiResponseHelpers;

    protected $gemini;

    public function __construct() {
        $this->gemini = Gemini::client(env('GEMINI_API_KEY'));
    }

    public function generateText($title)
    {
        try {
            $result = $this->gemini->geminiPro()->generateContent($title);

            return $result->text();
        } catch (\Exception $e) {
            $this->logError($e, $title);

            return $this->respondServerError('Có lỗi xảy ra, vui lòng thử lagi sau');
        }
    }
}
