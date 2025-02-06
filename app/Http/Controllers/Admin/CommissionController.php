<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Commissions\StoreCommissionRequest;
use App\Http\Requests\Admin\Commissions\UpdateCommissionRequest;
use App\Models\Comment;
use App\Models\Commission;
use Illuminate\Http\Request;

class CommissionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $commissions = Commission::query()->paginate(10);
        return view('commissions.index', compact('commissions'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('commissions.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCommissionRequest $request)
    {
        try {
            //code...
            // dd($request->all());
            $data = $request->validated();

            Commission::create($data);

            return redirect()->route('admin.commissions.index')->with('success', 'Thao tác thành công');

        } catch (\Exception $e) {
            //throw $th;

            // $this->logError($e);

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
        return view('commissions.show',compact('commission'));

    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $commission = Commission::findOrFail($id);
        return view('commissions.edit',compact('commission'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCommissionRequest $request, string $id)
    {
        try {
            //code...
            

            $data = $request->validated();
            dd($data);

            $commission = Commission::findOrFail($id);

            $commission->update($data);

            return back()->with('success', 'Thao tác thành công');

        } catch (\Exception $e) {
            //throw $th;

            // $this->logError($e);

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
