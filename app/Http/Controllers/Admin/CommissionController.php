<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Commissions\StoreCommissionRequest;
use App\Http\Requests\Admin\Commissions\UpdateCommissionRequest;
use App\Models\Comment;
use App\Models\Commission;
use App\Traits\LoggableTrait;
use Illuminate\Http\Request;

class CommissionController extends Controller
{
    use LoggableTrait;

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $title = 'Quản lý cấu hình lợi nhuận';
        $subTitle = 'Danh sách cấu hình lợi nhuận của hệ thống';

        $commissions = Commission::query()->paginate(10);
        return view('commissions.index', compact('commissions', 'title', 'subTitle'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $title = 'Quản lý cấu hình lợi nhuận';
        $subTitle = 'Tạo mới cấu hình lợi nhuận';

        return view('commissions.create', compact('title', 'subTitle'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCommissionRequest $request)
    {
        try {
            $data = $request->validated();

            Commission::create($data);

            return redirect()->route('admin.commissions.index')->with('success', 'Thao tác thành công');

        } catch (\Exception $e) {
            $this->logError($e, $request->all());

            return redirect()
                ->back()
                ->with('success', false);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $commission = Commission::findOrFail($id);
        return view('commissions.show', compact('commission'));

    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $commission = Commission::findOrFail($id);

        $title = 'Quản lý cấu hình lợi nhuận';
        $subTitle = 'Chỉnh sửa cấu hình lợi nhuận: ' . $commission->difficulty_level;

        return view('commissions.edit', compact('commission', 'title', 'subTitle'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCommissionRequest $request, string $id)
    {
        try {
            $data = $request->validated();

            $commission = Commission::findOrFail($id);

            $commission->update($data);

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
    public function destroy(string $id)
    {
        try {
            //code...
            $commission = Commission::findOrFail($id);

            $commission->delete();

            return response()->json($data = ['status' => 'success', 'message' => 'Mục đã được xóa.']);
        } catch (\Exception $e) {
            //throw $th;
            $this->logError($e);

            return response()->json($data = ['status' => 'error', 'message' => 'Lỗi thao tác.']);
        }
    }
}
