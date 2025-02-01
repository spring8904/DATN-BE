<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;


class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            'Technology',
            'Business',
            'Design',
            'Marketing',
            'Science',
            'Health',
            'Education',
            'Arts',
            'Literature',
            'Music'
        ];

        foreach ($categories as $category) {
            Category::query()->create([
                'name' => $category,
                'slug' => Str::slug($category),
            ]);
        }
    }
}