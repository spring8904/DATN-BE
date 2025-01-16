<?php

namespace App\Http\Controllers\API\Banner;

use App\Http\Controllers\Controller;
use App\Http\Requests\API\Banners\StoreBannerRequest;
use App\Http\Requests\API\Banners\UpdateBannerRequest;
use App\Models\Banner;
use App\Traits\LoggableTrait;
use App\Traits\UploadToCloudinaryTrait;
use Illuminate\Http\Request;

class BannerController extends Controller
{
    use LoggableTrait, UploadToCloudinaryTrait;
    const FOLDER = 'banners';
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $banners = Banner::query()->paginate(10);
        return response()->json($banners);
    }
    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreBannerRequest $request)
    {
        //

        // dd($request->all());
        try {
            $data = $request->all();

            if ($request->hasFile('image')) {
                $data['image'] = $this->uploadImage($request->file('image'), self::FOLDER);
            }

            $banner = Banner::query()->create($data);

            return response()->json([
                'success' => true,
                'message' => 'Thao tác thành công',
                'data' => $banner,
            ], 201);
        } catch (\Exception $e) {
            if (isset($data['image']) && !empty($data['image']) && filter_var($data['image'], FILTER_VALIDATE_URL)) {
                $this->deleteImage($data['image'], self::FOLDER);
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
            $banner = Banner::findOrFail($id);
            return response()->json($banner);
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
    public function update(UpdateBannerRequest $request, string $id)
    {
        //
        // dd($request->all());
        try {
            $data = $request->all();

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
            }else {
                $data['image'] = $imageOld;
            }

            $banner->update($data);

            return response()->json([
                'success' => true,
                'message' => 'Thao tác thành công',
            ], 201);
        } catch (\Exception $e) {

            if (isset($data['image']) && !empty($data['image']) && filter_var($data['image'], FILTER_VALIDATE_URL)) {
                $this->deleteImage($data['image'], self::FOLDER);
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
    public function destroy($id)
    {
        //

        try {

            $banner = Banner::query()->findOrFail($id);

            $banner->delete();

            if (!empty($banner->image) && filter_var($banner->image, FILTER_VALIDATE_URL)) {
                $this->deleteImage($banner->image,  self::FOLDER);
            }

            return response()->json([
                'status' => 'success',
                'message' => 'Xoá dữ liệu thành công'
            ]);
        } catch (\Exception $e) {

            $this->logError($e);

            return back()
                ->with('success', false)
                ->with('error', 'Lỗi.');
        }
    }
}
