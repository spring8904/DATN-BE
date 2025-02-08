<?php

namespace Database\Seeders;

use App\Models\Approvable;
use App\Models\User;
use App\Models\Course;
use Illuminate\Database\Seeder;

class ApprovableSeeder extends Seeder
{
    public function run(): void
    {
        $admins = User::whereHas('roles', function ($query) {
            $query->where('name', 'admin');
        })->pluck('id')->toArray();

        $instructors = User::whereHas('roles', function ($query) {
            $query->where('name', 'instructor');
        })->pluck('id')->toArray();

        $userIds = User::pluck('id')->toArray();
        $courseIds = Course::pluck('id')->toArray();

        for ($i = 0; $i < 50; $i++) {
            $status = fake()->randomElement(['pending', 'approved', 'rejected']);
            $approvableType = fake()->randomElement([User::class, Course::class]);
            $approvableId = $approvableType === User::class
                ? fake()->randomElement($userIds)
                : fake()->randomElement($courseIds);

            Approvable::create([
                'approver_id'      => fake()->optional()->randomElement($admins),
                'status'           => $status,
                'note'             => fake()->optional()->sentence(),
                'approvable_type'  => $approvableType,
                'approvable_id'    => $approvableId,
                'request_date'     => fake()->optional()->dateTimeBetween('-1 year', 'now'),
                'approved_at'      =>  $status == 'approved' ? fake()->optional()->dateTimeBetween('-1 year', 'now') : null,
                'rejected_at'      =>  $status == 'rejected' ? fake()->optional()->dateTimeBetween('-1 year', 'now') : null,
            ]);
        }
    }
}
