<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Course;
use App\Models\Post;
use App\Models\SystemFund;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;


class SystemFundSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $userId = User::query()->pluck('id')->toArray();
        $course_id = Course::query()->pluck('id')->toArray();
        $transaction_id = Transaction::query()->where([
            'status' => 'completed',
            'type' => 'invoice'
        ])->pluck('id')->toArray();

        for ($i = 0; $i <= 100; $i++) {
            $total_amount = rand(10000, 10000000);

            SystemFund::query()->create([
                'user_id' => fake()->randomElement($userId),
                'course_id' => fake()->randomElement($course_id),
                'transaction_id' => fake()->randomElement($transaction_id),
                'total_amount' => $total_amount,
                'retained_amount' => $total_amount * fake()->randomElement([0.2, 0.3, 0.4, 0.5, 0.1, 0.6]),
                'created_at' => fake()->randomElement([
                    '2024-01-13',
                    '2024-02-13',
                    '2024-03-13',
                    '2024-04-13',
                    '2024-05-13',
                    '2024-06-13',
                    '2024-07-13',
                    '2024-08-13',
                    '2024-09-13',
                    '2024-10-13',
                    '2024-12-13'
                ]),
            ]);
        }
    }
}
