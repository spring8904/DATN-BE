<?php

namespace App\Http\Controllers\API\Common;

use App\Http\Controllers\Controller;
use App\Http\Requests\API\Transaction\DepositTransactionRequest;
use App\Models\Coupon;
use App\Models\Course;
use App\Models\CourseUser;
use App\Models\Invoice;
use App\Models\SystemFund;
use App\Models\Transaction;
use App\Models\User;
use App\Models\Wallet;
use App\Notifications\UserBuyCourseNotification;
use App\Traits\ApiResponseTrait;
use App\Traits\LoggableTrait;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use PhpParser\Node\Stmt\Return_;

class TransactionController extends Controller
{
    use LoggableTrait, ApiResponseTrait;

    const adminRate = 0.4;
    const instructorRate = 1 - self::adminRate;
    const walletMail = 'superadmin@gmail.com';

    public function index()
    {
        try {
            $transactions = Transaction::query()->where('transactionable_id', Auth::id())->latest('id')->get();

            return $this->respondOk('Danh sách giao dịch của: ' . Auth::user()->name, $transactions);
        } catch (\Exception $e) {
            $this->logError($e);

            return $this->respondServerError('Có lỗi xảy ra, vui lòng thử lại');
        }
    }

    public function show(string $id)
    {
        try {
            $transaction = Transaction::query()->findOrFail($id);

            return $this->respondOk('Chi tiết giao dịch của: ' . Auth::user()->name, $transaction);
        } catch (\Exception $e) {
            $this->logError($e);

            return $this->respondServerError('Có lỗi xảy ra, vui lòng thử lại');
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
    public function createVNPayPayment(Request $request)
    {
        try {
            $validated = $request->validate([
                'amount' => 'required|numeric|min:1000',
                'order_info' => 'required|string',
            ]);

            $vnp_TmnCode = config('vnpay.vnp_TmnCode');
            $vnp_HashSecret = config('vnpay.vnp_HashSecret');
            $vnp_Url = config('vnpay.vnp_Url');
            $vnp_ReturnUrl = config('vnpay.vnp_ReturnUrl');

            $vnp_TxnRef = Str::random(10);
            $vnp_OrderInfo = $validated['order_info'];
            $vnp_Amount = $validated['amount'] * 100;
            $vnp_Locale = 'vn';
            $vnp_IpAddr = request()->ip();

            $inputData = [
                "vnp_Version" => "2.1.0",
                "vnp_TmnCode" => $vnp_TmnCode,
                "vnp_Amount" => $vnp_Amount,
                "vnp_Command" => "pay",
                "vnp_CreateDate" => now()->format('YmdHis'),
                "vnp_CurrCode" => "VND",
                "vnp_IpAddr" => $vnp_IpAddr,
                "vnp_Locale" => $vnp_Locale,
                "vnp_OrderInfo" => $vnp_OrderInfo,
                "vnp_OrderType" => "billpayment",
                "vnp_ReturnUrl" => $vnp_ReturnUrl,
                "vnp_TxnRef" => $vnp_TxnRef,
            ];

            ksort($inputData);
            $query = "";
            $i = 0;
            $hashdata = "";

            foreach ($inputData as $key => $value) {
                if ($i == 1) {
                    $hashdata .= '&' . urlencode($key) . "=" . urlencode($value);
                } else {
                    $hashdata .= urlencode($key) . "=" . urlencode($value);
                    $i = 1;
                }
                $query .= urlencode($key) . "=" . urlencode($value) . '&';
            }

            $vnp_Url = $vnp_Url . "?" . $query;
            if (isset($vnp_HashSecret)) {
                $vnpSecureHash = hash_hmac('sha512', $hashdata, $vnp_HashSecret);
                $vnp_Url .= 'vnp_SecureHash=' . $vnpSecureHash;
            }

            return response()->json([
                'status' => true,
                'message' => 'Tạo URL thanh toán thành công',
                'payment_url' => $vnp_Url,
            ], Response::HTTP_OK);
        } catch (\Exception $e) {
            $this->logError($e, $request->all());

            return $this->respondServerError('Có lỗi xảy ra, vui lòng thử lại');
        }
    }

    public function vnpayCallback(Request $request)
    {
        try {
            $vnp_HashSecret = config('vnpay.vnp_HashSecret');
            $inputData = $request->all();
            $parts = explode('-', json_encode($inputData['vnp_OrderInfo']));
            $invoiceId = trim(end($parts), '"');

            $vnp_SecureHash = $inputData['vnp_SecureHash'];
            unset($inputData['vnp_SecureHash']);
            ksort($inputData);
            $hashData = "";
            foreach ($inputData as $key => $value) {
                $hashData .= urlencode($key) . "=" . urlencode($value) . '&';
            }

            $hashData = rtrim($hashData, '&');
            $secureHash = hash_hmac('sha512', $hashData, $vnp_HashSecret);
            $hashData = rtrim($hashData, '&');

            if ($secureHash === $vnp_SecureHash) {
                if ($inputData['vnp_ResponseCode'] == '00') {
                    DB::beginTransaction();

                    $invoice = Invoice::where('id', $invoiceId)->first();

                    $invoice->update(['status' => 'Đã thanh toán']);

                    $course = Course::find($invoice->course_id);

                    $discount = null;

                    if (!empty($invoice->coupon_code)) {
                        $discount = Coupon::query()->where([
                            'code' => $invoice->coupon_code,
                            'status' => '1'
                        ])->first();
                    }

                    CourseUser::create([
                        'user_id' => $invoice->user_id,
                        'course_id' => $course->id,
                        'enrolled_at' => now(),
                    ]);

                    $transaction = Transaction::create([
                        'transaction_code' => $inputData['vnp_TxnRef'],
                        'user_id' => $invoice->user_id,
                        'amount' => $inputData['vnp_Amount'] / 100,
                        'transactionable_id' => $invoice->id,
                        'transactionable_type' => Invoice::class,
                        'status' => 'Giao dịch thành công',
                        'type' => 'invoice',
                    ]);

                    $this->finalBuyCourse($invoice->user_id, $course, $transaction, $discount, $inputData['vnp_Amount'] / 100);

                    DB::commit();

                    return response()->json([
                        'status' => true,
                        'message' => 'Thanh toán thành công',
                    ], Response::HTTP_OK);
                } else {
                    return response()->json([
                        'status' => false,
                        'message' => 'Thanh toán thất bại',
                    ], Response::HTTP_BAD_REQUEST);
                }
            } else {
                Log::info('khong hơp le');
                return response()->json([
                    'status' => false,
                    'message' => 'Chữ ký không hợp lệ',
                ], Response::HTTP_BAD_REQUEST);
            }
        } catch (\Exception $e) {
            $this->logError($e);

            return response()->json([
                'status' => false,
                'message' => 'Lỗi xử lý callback từ VNPAY',
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

            $course = Course::query()->where([
                'slug' => $request->slug,
                'status' => 'approved'
            ])->first();

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
                $discount = Coupon::query()->where([
                    'code' => $validated['discount_code'],
                    'status' => '1'
                ])->first();

                if (!empty($discount)) {
                    $discountAmount = ($discount->discount_type === 'percentage')
                        ? (($course->price_sale ?? $course->price) * $discount->discount_value) / 100
                        : $discount->discount_value;

                    $discountAmount = min($discountAmount, $course->price_sale ?? $course->price);
                } else {
                    return $this->respondError('Mã giảm giá không hợp lệ hoặc đã hết hạn');
                }
            }

            $finalAmount = round(max($validated['amount'] - $discountAmount, 0), 2);

            $invoice = Invoice::create([
                'user_id' => $userID,
                'course_id' => $course->id,
                'code' => 'HD' . strtoupper(Str::random(8)),
                'coupon_code' => $validated['discount_code'] ?? null,
                'coupon_discount' => $discountAmount > 0 ? $discountAmount : null,
                'total' => $validated['amount'],
                'final_total' => $finalAmount,
                'status' => $finalAmount === 0 ? 'Đã thanh toán' : 'Chờ thanh toán',
            ]);

            if ($finalAmount === 0) {
                $transaction = Transaction::create([
                    'transaction_code' => 'GD' . strtoupper(Str::random(8)),
                    'user_id' => $userID,
                    'amount' => $validated['amount'],
                    'transactionable_id' => $invoice->id,
                    'transactionable_type' => Invoice::class,
                    'status' => 'Giao dịch thành công',
                    'type' => 'invoice',
                ]);

                CourseUser::create([
                    'user_id' => $userID,
                    'course_id' => $course->id,
                    'enrolled_at' => now(),
                ]);

                $this->finalBuyCourse($userID, $course, $transaction, $discount);

                DB::commit();

                return $this->respondOk('Mua khóa học thành công');
            } else {
                DB::commit();
                $payment_method = !empty($request->payment_method) ? $request->payment_method : 'vnpay';

                if ($payment_method === 'bank') {
                    return $this->respondOk('Chưa có bank');
                } else {
                    $modifiedRequest = $request->merge([
                        'order_info' => 'thanh-toan-kho-hoc-' . $course->slug . '-' . $invoice->id
                    ]);

                    return $this->createVNPayPayment($modifiedRequest);
                }
            }
        } catch (\Exception $e) {
            DB::rollBack();

            $this->logError($e, $request->all());

            return $this->respondServerError('Có lỗi xảy ra, vui lòng thử lại');
        }
    }

    private function finalBuyCourse($userID, $course, $transaction, $discount = null, $finalAmount = null)
    {
        if ($discount) {
            $course->coupons()->attach($discount->id);
            $discount->decrement('used_count');
        }

        $course->increment('total_student');

        $walletInstructor = Wallet::query()->firstOrCreate(['user_id' => $course->user_id]);

        $walletInstructor->balance += $finalAmount * self::instructorRate;

        $walletInstructor->save();

        $walletWeb = Wallet::query()->firstOrCreate([
            'user_id' => User::where('email', self::walletMail)->value('id'),
        ]);

        $walletWeb->balance += $finalAmount * self::adminRate;

        $walletWeb->save();

        SystemFund::query()->create([
            'transaction_id' => $transaction->id,
            'course_id' => $course->id,
            'user_id' => $userID,
            'total_amount' => $finalAmount,
            'retained_amount' => $finalAmount * self::adminRate,
        ]);

        User::role('admin')->each(function ($manager) use ($course, $userID) {
            $manager->notify(new UserBuyCourseNotification(User::find($userID), $course->load('invoices.transaction')));
        });
    }
}
