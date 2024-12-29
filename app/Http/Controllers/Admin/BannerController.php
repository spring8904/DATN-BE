<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreBannerRequest;
use App\Models\Banner;
use App\Traits\LoggableTrait;
use App\Traits\UploadToCloudinaryTrait;
use Illuminate\Http\Request;

class BannerController extends Controller
{
    use LoggableTrait;
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
            // dd($request->validated());
            $data = $request->all();

            // Upload image to Cloudinary
            if ($request->hasFile('image')) {
                $uploadedFileUrl = cloudinary()->upload($request->file('image')->getRealPath())->getSecurePath();
                $data['image'] = $uploadedFileUrl;
            }
            Banner::query()->create($data);
            return redirect()->route('admin.banners.index')->with(['success' => true, 'uploaded_image' => $uploadedFileUrl]);
        } catch (\Exception $e) {

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
        // dd($banner);
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
            $banner = Banner::findOrFail($id);
            $data = $request->all();

            // Upload new image to Cloudinary if provided
            if ($request->hasFile('image')) {
                $uploadedFileUrl = cloudinary()->upload($request->file('image')->getRealPath())->getSecurePath();
                $data['image'] = $uploadedFileUrl;
            }

            $banner->update($data);

            return redirect()->route('admin.banners.edit',$banner->id)->with('success', true);
        } catch (\Exception $e) {
            $this->logError($e);
            return back()->with('success', false)->with('error', $e->getMessage());
        }
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Banner $banner)
    {
        try {
            $banner->delete();
            return redirect()
                ->route('admin.banners.index')
                ->with('success', true);
        } catch (\Exception $e) {
            return back()
                ->with('success', false)
                ->with('error', 'Lỗi.');
        }
    }
}
