<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Users\StoreUserRequest;
use App\Http\Requests\Admin\Users\UpdateUserRequest;
use App\Models\User;
use App\Traits\LoggableTrait;
use App\Traits\UploadToCloudinaryTrait;
use Illuminate\Support\Str;

class UserController extends Controller
{
    use LoggableTrait, UploadToCloudinaryTrait;
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {

            $title = 'Quản lý thành viên';
            $subTitle = 'Danh sách người dùng';

            $users = User::query()->latest('id')->paginate(10);
            $countUsers = User::query()->count('id');
            $userActive = User::query()->where('status', 'active')->count('id');
            $userInActive = User::query()->where('status', 'inactive')->count('id');
            $userBlocked = User::query()->where('status', 'blocked')->count('id');

            return view('users.index', compact([
                'users',
                'userActive',
                'userInActive',
                'userBlocked',
                'countUsers',
                'subTitle',
                'title'
            ]));
        } catch (\Exception $e) {

            $this->logError($e);

            return redirect()->back()->with('error', 'Có lỗi xảy ra, vui lòng thử lại sau');
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        try {

            $title = 'Quản lý thành viên';
            $subTitle = 'Thêm mới người dùng';

            return view('users.create', compact([
                'title',
                'subTitle'
            ]));
        } catch (\Exception $e) {

            $this->logError($e);

            return redirect()->back()->with('error', 'Có lỗi xảy ra, vui lòng thử lại sau');
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreUserRequest $request)
    {
        try {

            $validator = $request->validated();

            $data = $request->except('avatar');

            if ($request->hasFile('avatar')) {
                $data['avatar'] = $this->uploadImage($request->file('avatar'));
            }

            $data['code'] = str_replace('-', '', Str::uuid()->toString());

            User::query()->create($data);

            return redirect()->route('admin.users.index')->with('success', true);
        } catch (\Exception $e) {

            if (isset($data['avatar']) && !empty($data['avatar']) && filter_var($data['avatar'], FILTER_VALIDATE_URL)) {
                $this->deleteImage($data['avatar']);
            }

            $this->logError($e);

            return redirect()->back()->with('error', 'Có lỗi xảy ra, vui lòng thử lại sau');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {

            $title = 'Quản lý thành viên';
            $subTitle = 'Chi tiết người dùng';

            $user = User::query()->findOrFail($id);

            return view('users.show', compact([
                'user',
                'title',
                'subTitle'
            ]));
        } catch (\Exception $e) {

            $this->logError($e);

            return redirect()->back()->with('error', 'Có lỗi xảy ra, vui lòng thử lại sau');
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        try {

            $title = 'Quản lý thành viên';
            $subTitle = 'Cập nhật người dùng';

            $user = User::query()->findOrFail($id);

            return view('users.edit', compact([
                'user',
                'title',
                'subTitle'
            ]));
        } catch (\Exception $e) {

            $this->logError($e);

            return redirect()->back()->with('error', 'Có lỗi xảy ra, vui lòng thử lại sau');
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateUserRequest $request, string $id)
    {
        try {

            $validator = $request->validated();

            $data = $request->except('avatar');

            $user = User::query()->findOrFail($id);

            $currencyAvatar = $user->avatar;

            if ($request->hasFile('avatar')) {
                $data['avatar'] = $this->uploadImage($request->file('avatar'));
            }

            $user->update($data);

            if (
                isset($data['avatar']) && !empty($data['avatar'])
                && filter_var($data['avatar'], FILTER_VALIDATE_URL)
                && !empty($currencyAvatar)
            ) {
                $this->deleteImage($currencyAvatar);
            }

            return redirect()->route('admin.users.edit', $id)->with('success', true);
        } catch (\Exception $e) {

            if (isset($data['avatar']) && !empty($data['avatar']) && filter_var($data['avatar'], FILTER_VALIDATE_URL)) {
                $this->deleteImage($data['avatar']);
            }

            $this->logError($e);

            return redirect()->back()->with('error', 'Có lỗi xảy ra, vui lòng thử lại sau');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {

            if (str_contains($id, ',')) {

                $userID = explode(',', $id);

                $this->deleteUsers($userID);
            } else {
                $user = User::query()->findOrFail($id);

                $data['avatar'] = $user->avatar;

                $user->delete();
                if (isset($data['avatar']) && !empty($data['avatar']) && filter_var($data['avatar'], FILTER_VALIDATE_URL)) {
                    $this->deleteImage($data['avatar']);
                }
            }

            return response()->json([
                'status' => 'success',
                'message' => 'Xóa thành công'
            ]);
        } catch (\Exception $e) {

            $this->logError($e);

            return response()->json([
                'status' => 'error',
                'message' => 'Xóa thất bại'
            ]);
        }
    }

    private function deleteUsers(array $userID)
    {

        $users = User::query()->whereIn('id', $userID)->get();

        foreach ($users as $user) {

            $avatar = $user->avatar;

            $user->delete();

            if (isset($avatar) && !empty($avatar) && filter_var($avatar, FILTER_VALIDATE_URL)) {
                $this->deleteImage($avatar);
            }
        }
    }
}
