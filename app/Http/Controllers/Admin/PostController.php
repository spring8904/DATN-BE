<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Posts\StorePostRequest;
use App\Http\Requests\Admin\Posts\UpdatePostRequest;
use App\Models\Category;
use App\Models\Post;
use App\Models\Tag;
use App\Notifications\CrudNotification;
use App\Traits\LoggableTrait;
use App\Traits\UploadToCloudinaryTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use function PHPUnit\Framework\isEmpty;

class PostController extends Controller
{
    use LoggableTrait, UploadToCloudinaryTrait;

    const FOLDER = 'blogs';

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        try {

            $title = 'Quản lý bài viết';
            $subTitle = 'Danh sách bài viết';

            $categories = Category::query()->get();

            $searchPost = $request->input('searchPost');

            // kiểm tra xem có từ khóa không
            if (!empty($searchPost)) {
                $posts =  Post::with('user')
                    ->where('title', 'LIKE', '%' . $searchPost . '%')
                    ->paginate(10);
            } else {
                $posts = Post::with('user')->paginate(10);
            }

            return view('posts.index', compact([
                'title',
                'subTitle',
                'categories',
                'posts'
            ]));
        } catch (\Exception $e) {
            $this->logError($e);

            return redirect()->back()->with('error', 'Có lỗi xảy ra, vui lòng thử lại');
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        try {
            $title = 'Quản lý bài viết';
            $subTitle = 'Thêm mới bài viết';

            $categories = Category::query()->get();
            $tags = Tag::query()->get();

            return view('posts.create', compact([
                'title',
                'subTitle',
                'categories',
                'tags'
            ]));
        } catch (\Exception $e) {
            $this->logError($e);

            return redirect()->back()->with('error', 'Error: ' . $e->getMessage());
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StorePostRequest $request)
    {
        try {

            DB::beginTransaction();

            $data = $request->except('thumbnail');

            if ($request->hasFile('thumbnail')) {
                $data['thumbnail'] = $this->uploadImage($request->file('thumbnail'), self::FOLDER);
            }

            $data['user_id'] = Auth::id();

            $data['category_id'] = $request->input('categories')[0];

            do {
                $data['slug'] = Str::slug($request->title) . '?' . Str::uuid();
            } while (Post::query()->where('slug',$data['slug'])->exists());

            // dd($data);

            $posts = Post::query()->create($data);

            if (!empty($request->input('categories'))) {
                $posts->categories()->sync($request->input('categories'));
            }

            if (!empty($request->input('tags'))) {
                $posts->tags()->sync($request->input('tags'));
            }

            DB::commit();

            return redirect()->route('admin.posts.index')->with('success', 'Thao tác thành công');

        } catch (\Exception $e) {

            DB::rollBack();

            if (
                !empty($data['thumbnail'])
                && filter_var($data['thumbnail'], FILTER_VALIDATE_URL)
            ) {
                $this->deleteImage($data['thumbnail'], 'posts');
            }

            $this->logError($e);

            return redirect()->back()->with('error', 'Error: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {
            $title = 'Quản lý bài viết';
            $subTitle = 'Chi tiết bài viết';

            $post = Post::query()
            ->with(['tags:id,name', 'categories:id,name,parent_id', 'user:id,name'])
            ->findOrFail($id);

            return view('posts.show', compact([
                'title',
                'subTitle',
                'post',
            ]));
        } catch (\Exception $e) {
            $this->logError($e);

            return redirect()->back()->with('error', 'Không tìm thấy bài viết');
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        try {
            $title = 'Quản lý bài viết';
            $subTitle = 'Cập nhật bài viết';

            $categories = Category::query()->get();
            $tags = Tag::query()->get();
            $post = Post::query()
            ->with(['tags:id,name', 'categories:id,name,parent_id'])
            ->findOrFail($id);

            $categoryIds = $post->categories->pluck('id')->toArray();
            $tagIds = $post->tags->pluck('id')->toArray();

            return view('posts.edit', compact([
                'title',
                'subTitle',
                'categories',
                'tags',
                'post',
                'categoryIds',
                'tagIds'
            ]));
        } catch (\Exception $e) {
            $this->logError($e);

            return redirect()->back()->with('error', 'Không tìm thấy bài viết');
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePostRequest $request, string $id)
    {
        try {
            DB::beginTransaction();

            $validated = $request->validated();

            $data = $request->except('thumbnail', 'categories','is_hot');

            $post = Post::query()->with(['tags', 'categories'])->findOrFail($id);

            if ($request->hasFile('thumbnail')) {
                $data['thumbnail'] = $this->uploadImage($request->file('thumbnail'), self::FOLDER);
            }

            $data['is_hot'] = $request->input('is_hot') ?? 0;
            $data['category_id'] = $request->input('categories')[0];

            do {
                $data['slug'] = Str::slug($request->title) . '?' . Str::uuid();
            } while (Post::query()->where('slug',$data['slug'])->exists());

            $currencyThumbnail = $post->thumbnail;

            $post->update($data);

            $post->categories()->sync($request->input('categories'));

            $post->tags()->sync($request->input('tags'));

            DB::commit();

            if (
                isset($data['thumbnail']) && !empty($data['thumbnail'])
                && filter_var($data['thumbnail'], FILTER_VALIDATE_URL)
                && !empty($currencyThumbnail)
            ) {
                $this->deleteImage($currencyThumbnail, self::FOLDER);
            }

            CrudNotification::sendToMany([], $id);

            return redirect()->route('admin.posts.edit', $id)->with('success', 'Cập nhật bài viết thành công');
        } catch (\Exception $e) {
            DB::rollBack();

            if (isset($data['thumbnail']) && !empty($data['thumbnail']) && filter_var($data['thumbnail'], FILTER_VALIDATE_URL)) {
                $this->deleteImage($data['thumbnail'],self::FOLDER);
            }

            $this->logError($e);

            return redirect()->back()->with('error', 'Cập nhật bài viết không thành công');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Post $post)
    {
        try {
            //code...
            $post->delete();

            if(isset($category->icon))
            {
                $this->deleteImage($post->thubmnail, self::FOLDER);
            }
            return response()->json($data = ['status' => 'success', 'message' => 'Mục đã được xóa.']);
        } catch (\Exception $e) {
            //throw $th;
            $this->logError($e);

            return response()->json($data = ['status' => 'error', 'message' => 'Lỗi thao tác.']);
        }
    }
}
