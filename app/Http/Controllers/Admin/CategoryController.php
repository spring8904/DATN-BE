<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Categories\StoreCategoryRequest;
use App\Http\Requests\Admin\Categories\UpdateCategoryRequest;
use App\Models\Category;
use App\Traits\LoggableTrait;
use App\Traits\UploadToCloudinaryTrait;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    use LoggableTrait, UploadToCloudinaryTrait;

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $title = 'Quản lý danh mục';
        $subTitle = 'Danh sách danh mục';

        $categories = Category::query()->with('parent')->paginate(10);
        return view('categories.index', compact('categories', 'title', 'subTitle'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $title = 'Quản lý danh mục';
        $subTitle = 'Thêm mới danh mục';

        $categories = Category::query()->with('parent')->get();

        return view('categories.create', compact([
            'title',
            'subTitle',
            'categories'
        ]));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCategoryRequest $request)
    {
        try {
            $data = $request->validated();

            if ($data['parent_id']) {
                $parentCategory = Category::query()->find($data['parent_id']);
                if ($parentCategory && $parentCategory->hasGrandchildren()) {
                    return redirect()->back()->withErrors(['parent_id' => 'Bạn không thể chọn cấp con thứ 3.']);
                }
            }

            $data['status'] ??= 0;

            $data['slug'] = !empty($data['name']) ? Str::slug($data['name']) : null;

            Category::query()->create($data);

            return redirect()->route('admin.categories.index')
                ->with('success', 'Thao tác thành công');

        } catch (\Exception $e) {
            $this->logError($e, $request->all());

            return redirect()
                ->back()
                ->with('fasle', 'Thao tác không thành công');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $category = Category::findOrFail($id);
        return view('categories.show', compact('category'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Category $category)
    {
        $title = 'Quản lý danh mục';
        $subTitle = 'Chi tiết danh mục: ' . $category->name;

        $categories = Category::query()->whereNull('parent_id')->get();

        return view('categories.edit', compact('category', 'title', 'subTitle', 'categories'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCategoryRequest $request, string $id)
    {
        try {
            $category = Category::findOrFail($id);

            $data = $request->validated();

            $data['slug'] = !empty($data['name']) ? Str::slug($data['name']) : $category->slug;

            $data['status'] ??= 0;

            $category->update($data);

            if (
                !empty($data['icon'])
                && filter_var($data['icon'], FILTER_VALIDATE_URL)
                && !empty($currencyIcon)
            ) {
                $this->deleteImage($currencyIcon, 'categories');
            }

            return back()->with('success', 'Thao tác thành công');
        } catch (\Exception $e) {
            $this->logError($e, $request->all());

            return redirect()
                ->back()
                ->with('success', false);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Category $category)
    {
        try {
            if ($category->children->count() > 0) {
                return response()->json($data = ['status' => 'error', 'message' =>'Danh mục đang có cấp con.']);
            }

            $category->delete();

            return response()->json($data = ['status' => 'success', 'message' => 'Mục đã được xóa.']);
        } catch (\Exception $e) {
            $this->logError($e);

            return response()->json($data = ['status' => 'error', 'message' => 'Lỗi thao tác.']);
        }
    }
}
