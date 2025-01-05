<?php

namespace App\Http\Controllers\API\Search;

use App\Http\Controllers\Controller;
use App\Http\Requests\API\Search\SearchRequest;
use App\Traits\LoggableTrait;
use F9Web\ApiResponseHelpers;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;

class SearchController extends Controller
{
    use LoggableTrait, ApiResponseHelpers;
    public function search(SearchRequest $request)
    {
        try {
            $data = $request->validated();

            $query = $request->input('query');
            $page = $request->input('page', 1);
            $perPage = $request->input('per_page', 10);

            $courses = DB::table('courses')
                ->select('id', 'name', 'slug', 'price', 'price_sale', 'thumbnail', 'total_student', 'duration', 'description')
                ->where('status', 'approved')
                ->where(function ($q) use ($query) {
                    $q->where('name', 'like', '%' . $query . '%')
                        ->orWhere('description', 'like', '%' . $query . '%');
                })
                ->paginate($perPage);

            $posts = DB::table('posts')
                ->select('id', 'title', 'slug', 'thumbnail', 'is_hot', 'published_at', 'content')
                ->where('status', 'published')
                ->where(function ($q) use ($query) {
                    $q->where('title', 'like', '%' . $query . '%')
                        ->orWhere('content', 'like', '%' . $query . '%')
                        ->orWhere('description', 'like', '%' . $query . '%');
                })
                ->paginate($perPage);
            return response()->json([
                'status' => true,
                'perpage' => $perPage,
                'page' => $page,
                'courses' => $courses,
                'posts' => $posts,
            ], Response::HTTP_OK);
        } catch (\Exception $e) {
            $this->logError($e);

            return response()->json([
                'status' => false,
                'message' => 'Có lỗi xảy ra, vui lòng thử lại',
                'data' => $request->all(),
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
