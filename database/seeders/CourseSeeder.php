<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\User;
use App\Models\Course;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CourseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = User::query()->pluck('id')->all();
        $categories = Category::query()->pluck('id')->all();

        for ($i = 1; $i <= 100; $i++) {
            $name = fake()->title() . Str::uuid();
            $slug = Str::slug($name);

            Course::query()->create([
                'user_id' => fake()->randomElement($users),
                'category_id' => fake()->randomElement($categories),
                'code' => substr(str_replace('-', '', (string) Str::uuid()), 0, 10),
                'name' => $name,
                'slug' => $slug,
                'price' => fake()->randomFloat(2, 10000, 500000),
                'price_sale' => fake()->randomFloat(2, 5000, 400000),
                'level' => fake()->randomElement(['beginner', 'intermediate', 'advanced']),
                'total_student' => random_int(0,100),
                'status' => fake()->randomElement(['draft', 'pending', 'approved', 'rejected']),
                'accepted' => fake()->dateTimeBetween('-1 year', 'now'),
            ]);
        }
    }
}
