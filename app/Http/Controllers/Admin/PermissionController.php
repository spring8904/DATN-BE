<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Permissions\StorePermissionRequest;
use App\Traits\LoggableTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Permission;

class PermissionController extends Controller
{
    use LoggableTrait;

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $permissions = Permission::all()->groupBy('guard_name');

            $title = 'Quản lý quyền';
            $subTitle = 'Danh sách quyền của hệ thống';

            return view('permissions.index', compact([
                'title',
                'subTitle',
                'permissions'
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
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StorePermissionRequest $request)
    {
        try {
            $data = $request->validated();

            Permission::query()->create($data);

            return redirect()->route('admin.permissions.index')->with('success', 'Thao tác thành công');
        } catch (\Exception $e) {
            $this->logError($e);

            return redirect()->route('admin.permissions.index')->with('error', 'Lỗi: ' . $e->getMessage());
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
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
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
        try {
            DB::beginTransaction();

            $permission = Permission::query()->findOrFail($id);

            if (!$permission) {
                return redirect()->back()->with('error', 'Không tìm thấy quyền');
            }

            $permission->delete();

            DB::commit();

            return response()->json([
                'status' => 'success',
                'message' => 'Xoá dữ liệu thành công'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();

            $this->logError($e);

            return redirect()->back()->with('error', 'Có lỗi xảy ra, vui lòng thử lại sau');
        }
    }
}
