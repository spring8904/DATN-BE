<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreBannerRequest;
use App\Models\Banner;
use App\Traits\LoggableTrait;
use App\Traits\UploadToCloudinaryTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BannerController extends Controller
{
    use LoggableTrait, UploadToCloudinaryTrait;

    const FOLDER = 'banners';

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Banner::query();

        // Kiểm tra nếu có từ khóa tìm kiếm
        if ($request->has('query') && $request->input('query')) {
            $search = $request->input('query');
            $query->where('title', 'like', "%$search%")
                ->orWhere('content', 'like', "%$search%");
        }

        // Lấy dữ liệu và phân trang
        $banners = $query->orderBy('created_at', 'desc')->paginate(10);

        return view('banners.index', compact('banners'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        try {
            return view('banners.create');
        } catch (\Exception $e) {

            $this->logError($e);

            return redirect()->back()->with('error', 'Có lỗi xảy ra, vui lòng thử lại sau');
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreBannerRequest $request)
    {
        try {
            DB::beginTransaction();

            $data = $request->all();

            if ($request->hasFile('image')) {
                $data['image'] = $this->uploadImage($request->file('image'), self::FOLDER);
            }

            Banner::query()->create($data);

            DB::commit();

            return redirect()->route('admin.banners.index')->with('success', true);
        } catch (\Exception $e) {
            DB::rollBack();

            if (isset($data['image']) && !empty($data['image']) && filter_var($data['image'], FILTER_VALIDATE_URL)) {
                $this->deleteImage($data['image'], self::FOLDER);
            }

            $this->logError($e);

            return redirect()->back()->with('error', $e->getMessage());
        }
    }


    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $banner = Banner::findOrFail($id);
        return view('banners.show', compact('banner'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $banner = Banner::findOrFail($id);
        return view('banners.edit', compact('banner'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(StoreBannerRequest $request, $id)
    {
        try {
            $data = $request->all();

            DB::beginTransaction();

            $banner = Banner::findOrFail($id);

            $imageOld = $banner->image;

            if ($request->hasFile('image')) {
                $data['image'] = $this->uploadImage($request->file('image'), self::FOLDER);

                if (
                    isset($data['image']) && !empty($data['image'])
                    && filter_var($data['image'], FILTER_VALIDATE_URL)
                    && !empty($imageOld)
                ) {
                    $this->deleteImage($imageOld, self::FOLDER);
                }
            }

            $banner->update($data);

            DB::commit();

            return redirect()->route('admin.banners.edit', $banner->id)->with('success', true);
        } catch (\Exception $e) {

            DB::rollBack();

            if (isset($data['image']) && !empty($data['image']) && filter_var($data['image'], FILTER_VALIDATE_URL)) {
                $this->deleteImage($data['image'], self::FOLDER);
            }

            $this->logError($e);

            return back()->with('success', false)->with('error', $e->getMessage());
        }
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            DB::beginTransaction();

            $banner = Banner::query()->findOrFail($id);

            $banner->delete();

            if (!empty($banner->image) && filter_var($banner->image, FILTER_VALIDATE_URL)) {
                $this->deleteImage($banner->image,  self::FOLDER);
            }

            DB::commit();

            return response()->json([
                'status' => 'success',
                'message' => 'Xoá dữ liệu thành công'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();

            $this->logError($e);

            return back()
                ->with('success', false)
                ->with('error', 'Lỗi.');
        }
    }
}
