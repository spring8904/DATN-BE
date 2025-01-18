<?php

namespace App\Http\Controllers\API\Common;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Categories\StoreCategoryRequest;
use App\Http\Requests\Admin\Categories\UpdateCategoryRequest;
use App\Models\Category;
use App\Traits\LoggableTrait;
use App\Traits\UploadToCloudinaryTrait;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    use LoggableTrait, UploadToCloudinaryTrait;
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $categories = Category::query()->with('parent')->get();
        return response()->json($categories);
    }
    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCategoryRequest $request)
    {
        //

        // dd($request->all());

        try {
            $validate = $request->validated();

            $data = $request->except('icon');

            $data['status'] ??= 0;

            do {
                $data['slug'] = Str::slug($data['name']) . '-' . Str::uuid();
                $exists = Category::query()->where('slug',$data['slug'])->exists();
            } while ($exists);

            if ($request->hasFile('icon')) {
                $data['icon'] = $this->uploadImage($request->file('icon'), 'categories');
            }

            $category = Category::query()->create($data);

            return response()->json([
                'success' => true,
                'message' => 'Thao tác thành công',
                'data' => $category,
            ], 201);

        } catch (\Exception $e) {
            //throw $th;

            if (
                !empty($data['icon'])
                && filter_var($data['icon'], FILTER_VALIDATE_URL)
                )
            {
                $this->deleteImage($data['icon'], 'categories');
            }

            $this->logError($e);

            return response()->json([
                'success' => false,
                'message' => 'Thao tác không thành công',
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
        try {
            $category = Category::findOrFail($id);
            return response()->json($category);
        } catch (\Exception $e) {
            $this->logError($e);
            return response()->json([
                'status'=>false,
                'message'=>'Lấy dữ liệu lỗi',
            ],500);
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCategoryRequest $request, string $id)
    {
        //
        // dd($request->all());

        try {

            $category = Category::findOrFail($id);
            $validate = $request->validated();
            $data = $request->except('icon');

            $data['status'] ??= 0;

            if ($request->hasFile('icon')) {
                $data['icon'] = $this->uploadImage($request->file('icon'), 'categories');
            }

            $currencyIcon = $category->icon;

            $category->update($data);

            // kiem tra truong icon co tin tai hay khong , url co hop le hay khong va url cu co hay khong

            if (
                !empty($data['icon'])
                && filter_var($data['icon'], FILTER_VALIDATE_URL)
                && !empty($currencyIcon)
            ) {
                $this->deleteImage($currencyIcon,'categories');
            }

            return response()->json([
                'success' => true,
                'message' => 'Thao tác thành công',
                'data' => $category,
            ], 201);
        } catch (\Exception $e) {
            //throw $th;
            if (
                !empty($data['icon'])
                && filter_var($data['icon'], FILTER_VALIDATE_URL)
            ) {
                $this->deleteImage($data['icon'], 'categories');
            }

            $this->logError($e);

            return response()->json([
                'success' => false,
                'message' => 'Thao tác không thành công',
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Category $category)
    {
        //

        try {
            //code...
            $category->delete();

            if(isset($category->icon))
            {
                $this->deleteImage($category->icon, 'categories');
            }
            return response()->json($data = ['status' => 'success', 'message' => 'Mục đã được xóa.']);
        } catch (\Exception $e) {
            //throw $th;
            $this->logError($e);

            return response()->json($data = ['status' => 'error', 'message' => 'Lỗi thao tác.']);
        }
    }
}
