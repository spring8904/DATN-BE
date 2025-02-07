<?php

namespace App\Http\Controllers\API\Common;

use App\Http\Controllers\Controller;
use App\Http\Requests\API\Transaction\DepositTransactionRequest;
use App\Models\Coupon;
use App\Models\Course;
use App\Models\CourseUser;
use App\Models\Invoice;
use App\Models\Transaction;
use App\Models\User;
use App\Notifications\UserBuyCourseNotification;
use App\Traits\LoggableTrait;
use F9Web\ApiResponseHelpers;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class TransactionController extends Controller
{
    use LoggableTrait, ApiResponseHelpers;

    public function index()
    {
        try {
            $transactions = Transaction::query()->where('transactionable_id', Auth::id())->latest('id')->get();

            return response()->json([
                'message' => 'Danh sách giao dịch của: ' . Auth::user()->name,
                'transactions' => $transactions,
            ], Response::HTTP_OK);
        } catch (\Exception $e) {
            $this->logError($e);

            return response()->json([
                'status' => false,
                'message' => 'Có lỗi xảy ra, vui lòng thử lại',
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function show(string $id)
    {
        try {
            $transaction = Transaction::query()->findOrFail($id);

            return response()->json([
                'message' => 'Chi tiết giao dịch của: ' . Auth::user()->name,
                'transactions' => $transaction,
            ], Response::HTTP_OK);
        } catch (\Exception $e) {
            $this->logError($e);

            return response()->json([
                'status' => false,
                'message' => 'Không tìm thấy giao dịch',
            ], Response::HTTP_NOT_FOUND);
        }
    }

    public function deposit(DepositTransactionRequest $request)
    {
        try {
            $data = $request->validated();

            $deposit = Transaction::query()->create([
                'amount' => $request->amount,
                'coin' => round($request->amount / 1000, 2),
                'transactionable_id' => Auth::id(),
                'transactionable_type' => 'App\Models\User',
            ]);

            return response()->json([
                'message' => 'Giao dịch nạp tiền đang chờ xử lý',
                'deposit' => $deposit,
            ], Response::HTTP_CREATED);
        } catch (\Exception $e) {
            $this->logError($e);

            return response()->json([
                'status' => false,
                'message' => 'Nạp tiền thất bại, vui lòng thử lại',
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function buyCourse(Request $request)
    {
        try {
            $validated = $request->validate([
                'slug' => 'required|exists:courses,slug',
                'amount' => 'required|numeric',
                'discount_code' => 'nullable|string',
            ]);

            DB::beginTransaction();

            $userID = Auth::id();

            if (!$userID) {
                return $this->respondUnAuthenticated('Vui lòng đăng nhập để mua khóa học');
            }

            $course = Course::query()->where('slug', $request->slug)->first();

            if (!$course) {
                return $this->respondError('Không tìm thấy khóa học');
            }

            if (CourseUser::where([
                'user_id' => $userID,
                'course_id' => $course->id,
            ])->exists()) {
                return $this->respondError('Bạn đã mua khóa học này rồi');
            }

            $discountAmount = 0;
            $discount = null;
            if (!empty($validated['discount_code'])) {
                $discount = Coupon::query()->where('code', $validated['discount_code'])->first();

                if ($discount) {
                    $discountAmount = ($discount->type === 'percentage')
                        ? ($course->price * $discount->discount_value) / 100
                        : $discount->discount_value;

                    $discountAmount = min($discountAmount, $course->price);
                } else {
                    return $this->respondError('Mã giảm giá không hợp lệ hoặc đã hết hạn');
                }
            }

            $finalAmount = max($validated['amount'] - $discountAmount, 0);

            CourseUser::create([
                'user_id' => $userID,
                'course_id' => $course->id,
                'enrolled_at' => now(),
            ]);

            $invoice = Invoice::create([
                'user_id' => $userID,
                'course_id' => $course->id,
                'code' => 'HD' . strtoupper(Str::random(8)),
                'coupon_code' => $validated['discount_code'] ?? null,
                'coupon_discount' => $discountAmount > 0 ? $discountAmount : null,
                'total' => $validated['amount'],
                'final_total' => $finalAmount,
                'status' => 'Đã thanh toán',
            ]);

            Transaction::create([
                'transaction_code' => 'GD' . strtoupper(Str::random(8)),
                'amount' => $validated['amount'],
                'transactionable_id' => $invoice->id,
                'transactionable_type' => Invoice::class,
                'status' => 'Giao dịch thành công',
                'type' => 'invoice',
            ]);

            if ($discount) {
                $course->coupons()->attach($discount->id);

                $discount->decrement('used_count');
            }

            $course->increment('total_student');

            User::role('admin')->each(function ($manager) use ($course) {
                $manager->notify(new UserBuyCourseNotification(Auth::user(), $course->load('invoices.transaction')));
            });

            DB::commit();

            return $this->respondOk('Mua khóa học thành công');
        } catch (\Exception $e) {
            DB::rollBack();

            $this->logError($e, $request->all());

            return $this->respondServerError('Có lỗi xảy ra, vui lòng thử lại');
        }
    }

}
