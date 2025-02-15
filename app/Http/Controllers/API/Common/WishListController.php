<?php

namespace App\Http\Controllers\API\Common;

use App\Http\Controllers\Controller;
use App\Http\Requests\API\WishList\StoreWishListRequest;
use App\Models\WishList;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WishListController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        try {

            $user = Auth::user();

            $wishLists = WishList::query()
                ->where('user_id', $user->id)
                ->with('courses')
                ->get();

            if ($wishLists->isEmpty()) {
                return $this->respondNotFound('Không có dữ liệu');
            }

            return $this->respondOk('Danh sách người dùng', $wishLists);
        } catch (\Exception $e) {
            $this->logError($e);

            return $this->respondServerError('Có lỗi xảy ra, vui lòng thử lại');
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreWishListRequest $request)
    {
        //
        try {

            $user = Auth::user();

            // Kiểm tra nếu đã tồn tại trong danh sách yêu thích
            $existingWishList = WishList::where('user_id', $user->id)
                ->where('course_id', $request->course_id)
                ->exists();

            if ($existingWishList) {
                return response()->json(['message' => 'Khóa học này đã có trong danh sách yêu thích'], 409);
            }

            // Thêm khóa học vào danh sách yêu thích

            $wishList = WishList::create([
                'user_id' => $user->id,
                'course_id' => $request->course_id,
            ]);

            return response()->json([
                'message' => 'Khóa học đã được thêm  vào danh sách yêu thích',
                'wishList' => $wishList
            ], 201);
        } catch (\Exception $e) {
            $this->logError($e);

            return $this->respondServerError('Có lỗi xảy ra, vui lòng thử lại');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
        try {

            $user = Auth::user();

            $wishList = WishList::where('user_id', $user->id)
                ->find($id);
                
            if (!$wishList) {
                return response()->json(['message' => 'Không tìm thấy mục yêu thích'], 404);
            }

            $wishList->delete();

            return response()->json(['message' => 'Đã xóa khóa học khỏi danh sách yêu thích'], 200);

        } catch (\Exception $e) {
            $this->logError($e);

            return $this->respondServerError('Có lỗi xảy ra, vui lòng thử lại');
        }
    }
}
