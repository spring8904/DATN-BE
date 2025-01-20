<?php

namespace App\Http\Controllers\API\Common;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Post;
use App\Traits\LoggableTrait;
use F9Web\ApiResponseHelpers;
use Illuminate\Http\Request;

class PostController extends Controller
{
    use LoggableTrait, ApiResponseHelpers;

    public function index(Request $request)
    {
        try {
            $searchPost = $request->input('search');

            // kiểm tra xem có từ khóa không
            if (!empty($searchPost)) {
                $posts = Post::with('user')
                    ->where('title', 'LIKE', '%' . $searchPost . '%')
                    ->paginate(10);
            } else {
                $posts = Post::with('user')->paginate(10);
            }

            if ($posts->isEmpty()) {
                return $this->respondNotFound('Không tìm thấy bài viết');
            }

            return $this->respondOk('Danh sách bài viết', $posts);
        } catch (\Exception $e) {
            $this->logError($e);

            return $this->respondServerError('Có lỗi xảy ra, vui lòng thử lại');
        }
    }

}
