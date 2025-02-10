<?php

namespace App\Http\Controllers\API\Common;

use App\Http\Controllers\Controller;
use App\Http\Requests\API\Ratings\StoreRatingRequest;
use App\Models\CourseUser;
use App\Models\Rating;
use App\Traits\LoggableTrait;
use F9Web\ApiResponseHelpers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RatingController extends Controller
{
    use LoggableTrait, ApiResponseHelpers;

    public function store(StoreRatingRequest $request)
    {
        if (!Auth::check()) {
            return response()->json([
                'status' => false,
                'message' => 'Bạn cần đăng nhập để đánh giá khóa học.'
            ], 401);
        }
    
        $userId = Auth::id();
    
        $data = $request->except('user_id');
    
        // Kiểm tra xem user đã hoàn thành khóa học chưa
        $completed = CourseUser::where([
            'user_id' => $userId,
            'course_id' => $data['course_id']
        ])->value('progress_percent') === 100;
    
        if (!$completed) {
            return response()->json([
                'status' => false,
                'message' => 'Bạn phải hoàn thành khóa học trước khi đánh giá.'
            ], 403);
        }
    
        // Cập nhật hoặc tạo mới đánh giá
        $rates = Rating::updateOrCreate(
            ['user_id' => $userId, 'course_id' => $data['course_id']],
            ['content' => $data['content'], 'rate' => $data['rate']]
        );
    
        return response()->json([
            'status' => true,
            'message' => 'Đánh giá thành công.',
            'rates' => $rates
        ], 201);
    }
    public function index($courseId)
    {
        try {
            $ratings = Rating::where('course_id', $courseId)
                ->with('user:id,name') // Lấy thông tin user
                ->latest()
                ->paginate(10);
            if ($ratings->isEmpty()) {
                return $this->respondNotFound('Không tìm thấy đánh giá');
            }
            return response()->json([
                'status' => true,
                'ratings' => $ratings
            ]);
        } catch (\Exception $e) {
            $this->logError($e);

            return $this->respondServerError('Có lỗi xảy ra, vui lòng thử lại');
        }
    }
}
