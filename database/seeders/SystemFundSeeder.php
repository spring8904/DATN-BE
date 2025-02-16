<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Course;
use App\Models\Post;
use App\Models\SystemFund;
use App\Models\Transaction;
use App\Models\User;
use App\Models\Wallet;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;


class SystemFundSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $userId = User::query()->where('email', 'superadmin@gmail.com')->pluck('id')->first();
        $userIDs = User::query()->pluck('id')->toArray();
        $course_id = Course::query()->pluck('id')->toArray();
        $course_name = Course::query()->pluck('name')->toArray();
        $transaction_id = Transaction::query()->where([
            'status' => 'completed',
            'type' => 'invoice'
        ])->pluck('id')->toArray();

        for ($i = 0; $i <= 1000; $i++) {
            $total_amount = rand(10000, 10000000);
            $type = fake()->randomElement(['commission_received', 'withdrawal']);
            $description = $type == 'commission_received' ? 'Nhận tiền hoa hồng từ việc bán khóa học' . fake()->randomElement($course_name) : 'Rút tiền tài khoản';
            $retained_amount = $type == 'commission_received' ? $total_amount * fake()->randomElement([0.2, 0.3, 0.4, 0.5, 0.1, 0.6]) : 0;
            SystemFund::query()->create([
                'user_id' => fake()->randomElement($userIDs),
                'course_id' => fake()->randomElement($course_id),
                'transaction_id' => fake()->randomElement($transaction_id),
                'total_amount' => $total_amount,
                'retained_amount' => $retained_amount,
                'type' => $type,
                'description' => $description,
                'created_at' => fake()->dateTimeBetween('-1 year', now(env('APP_TIMEZONE'))),
            ]);

            $wallet = Wallet::query()->firstOrCreate(['user_id' => $userId]);
            $wallet->balance = $type == 'commission_received' ? ($wallet->balance + $retained_amount) : max($wallet->balance - $total_amount,0);
            $wallet->save();
        }
    }
}
