<?php

namespace App\Http\Controllers\API\Common;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Posts\StorePostRequest;
use App\Models\Category;
use App\Models\Post;
use App\Models\Tag;
use App\Traits\LoggableTrait;
use App\Traits\UploadToCloudinaryTrait;
use F9Web\ApiResponseHelpers;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class PostController extends Controller
{
    use LoggableTrait, ApiResponseHelpers, UploadToCloudinaryTrait;
    const FOLDER = 'blogs';

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

            return response()->json([
                'status' =>true,
                'posts'=>$posts
            ], 200);
        } catch (\Exception $e) {
            $this->logError($e);

            return $this->respondServerError('Có lỗi xảy ra, vui lòng thử lại');
        }
    }
    public function store(StorePostRequest $request)
    {
        // Log::info('User ID:', ['id' => Auth::id()]);
        // Log::info('User Object:', ['user' => Auth::user()]);

        try {
            DB::beginTransaction();
            $data = $request->except('thumbnail');

            if ($request->hasFile('thumbnail')) {
                $data['thumbnail'] = $this->uploadImage($request->file('thumbnail'), self::FOLDER);
            }

            $data['user_id'] = Auth::id();

            $data['category_id'] = $request->input('categories');

            $data['published_at'] = $request->input('published_at') ?? now();

            do {
                $data['slug'] = Str::slug($request->title) . '-' . substr(Str::uuid(), 0, 10);
            } while (Post::query()->where('slug', $data['slug'])->exists());

            $post = Post::query()->create($data);

            if (!empty($request->input('tags'))) {
                $tags = collect($request->input('tags'))->map(function ($tagName) {
                    return Tag::query()->firstOrCreate([
                        'name' => $tagName,
                        'slug' => Str::slug($tagName) ?? Str::uuid()
                    ]);
                });

                $post->tags()->sync($tags->pluck('id'));
            }
            DB::commit();
            return response()->json([
                'status' =>true,
                'message'=>'Thêm mới bài viết thành công'
            ], Response::HTTP_CREATED);
        } catch (\Exception $e) {

            DB::rollBack();

            if (
                !empty($data['thumbnail'])
                && filter_var($data['thumbnail'], FILTER_VALIDATE_URL)
            ) {
                $this->deleteImage($data['thumbnail'], 'posts');
            }

            $this->logError($e);

            return response()->json([
                'status' => false,
                'message' => 'Có lỗi xảy ra, vui lòng thử lại'
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
