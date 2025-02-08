<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Coupons\StoreCouponRequest;
use App\Http\Requests\Admin\Coupons\UpdateCouponRequest;
use App\Models\Coupon;
use App\Traits\LoggableTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CouponController extends Controller
{
    use LoggableTrait;
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $queryCoupons = Coupon::query();

        // Kiểm tra nếu có từ khóa tìm kiếm
        if ($request->has('query') && $request->input('query')) {
            $search = $request->input('query');
            $queryCoupons->where('name', 'like', "%$search%")
                ->orWhere('code', 'like', "%$search%");
        }
        $queryCouponCounts = Coupon::query()
        ->selectRaw('
            COUNT(id) as total_coupons,
            SUM(CASE WHEN status = 1 THEN 1 ELSE 0 END) as active_coupons,
            SUM(CASE WHEN expire_date < NOW() THEN 1 ELSE 0 END) as expire_coupons,
            SUM(CASE WHEN used_count > 0 THEN 1 ELSE 0 END) as used_coupons
        ');    
        if ($request->hasAny(['code','name','user_id','discount_type','status','used_count','start_date','expire_date'])) {
           $queryCoupons = $this->filter($request,$queryCoupons);
        }
        // Lấy dữ liệu và phân trang
        $coupons = $queryCoupons->orderBy('id', 'desc')->paginate(10);
        $couponCounts = $queryCouponCounts->first();
        if ($request->ajax()) {
            $html = view('coupons.table', compact('coupons'))->render();
            return response()->json(['html' => $html]);
        }
        return view('coupons.index', compact('coupons','couponCounts'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('coupons.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCouponRequest $request)
    {
        try {
            DB::beginTransaction();
            $data = $request->all();
            // $data['user_id'] = auth()->id();
            Coupon::query()->create($data);
            DB::commit();
            return redirect()->route('admin.coupons.index')->with('success', 'Thêm mới thành công');
        } catch (\Exception $e) {
            DB::rollBack();
            $this->logError($e);
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $coupon = Coupon::findOrFail($id);
        return view('coupons.show', compact('coupon'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $coupon = Coupon::findOrFail($id);
        return view('coupons.edit', compact('coupon'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCouponRequest $request, string $id)
    {
        try {
            DB::beginTransaction();
            $coupon = Coupon::findOrFail($id);
            $data = $request->validated();
            

            $coupon->update($data);
            DB::commit();
            return redirect()->route('admin.coupons.edit', $coupon->id)->with('success', 'Cập nhật thành công');
        } catch (\Exception $e) {
            DB::rollBack();
            $this->logError($e);
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            DB::beginTransaction();
            $coupon = Coupon::query()->findOrFail($id);

            $coupon->delete();
            DB::commit();
            return response()->json([
                'status' => 'success',
                'message' => 'Xóa dữ liệu thành công'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            $this->logError($e);
            return back()->with('success', false)->with('error', 'Lỗi');
        }
    }
    private function filter($request, $query)
    {
        $filters = [
            'start_date' => ['queryWhere' => '>='],
            'expire_date' => ['queryWhere' => '<='],
            'code' => ['queryWhere' => 'LIKE'],
            'name' => ['queryWhere' => 'LIKE'],
            'user_id' => ['queryWhere' => 'LIKE'],
            'status' => ['queryWhere' => '='],
            'discount_type' => ['queryWhere' => '='],
            'used_count' => ['queryWhere' => '<=']
        ];
    
        foreach ($filters as $filter => $value) {
            $filterValue = $request->input($filter);
    
            if ($filterValue !== null) {
                if (is_array($value) && !empty($value['queryWhere'])) {
                    
                    if ($value['queryWhere'] === 'BETWEEN') {
                            $query->whereBetween($filter, [$filterValue, 10000]);
                    } else {
                        $filterValue = $value['queryWhere'] === 'LIKE' ? "%$filterValue%" : $filterValue;
                        $query->where($filter, $value['queryWhere'], $filterValue);
                    }
                }
            }
        }
    
        return $query;
    }
    
}
