<?php

namespace Database\Seeders;

use App\Models\Course;
use App\Models\Invoice;
use App\Models\User;
use App\Models\Wallet;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class InvoiceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = User::query()->whereHas('roles', function($query){
            $query->where('name', 'member');
        })->pluck('id')->toArray();

        $courses = Course::query()->where('status', 'approved')->pluck('id')->toArray();

        foreach ($users as $user) {
            Invoice::create([
                'user_id' => $user,
                'course_id' => fake()->randomElement($courses),
                'total' => fake()->randomFloat(2, 10000, 10000000),
                'final_total' => fake()->randomFloat(2, 10000, 10000000),
                'status' => fake()->randomElement(['completed', 'pending', 'failed']),
            ]);
        }
    }
}
