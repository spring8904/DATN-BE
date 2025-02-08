<?php

namespace Database\Seeders;

use App\Models\Coupon;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;


class CouponSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $userId = User::query()->pluck('id')->toArray();

        for ($i = 0; $i <= 100; $i++) {
            $start_date = fake()->dateTimeBetween('-1 year', 'now');
            $discount_type = fake()->randomElement(['percentage', 'fixed']);
            $discount_value = $discount_type == "percentage" ? rand(0,100) : rand(10000,1000000);
            
            Coupon::query()->create([
                'user_id' => fake()->randomElement($userId),
                'code' => substr(str_replace('-','',Str::uuid()),0,10),
                'name' => fake()->title(),
                'discount_type' => $discount_type,
                'discount_value' => $discount_value,
                'start_date' => $start_date,
                'expire_date' => fake()->dateTimeBetween($start_date,'now + 1 day'),
                'description' => fake()->paragraph(2),
                'used_count' => rand(1,1000),
                'status' => random_int(0,1),
            ]);
        }
    }
}
