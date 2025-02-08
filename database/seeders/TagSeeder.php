<?php

namespace Database\Seeders;

use App\Models\Tag;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Faker\Factory as Faker;

class TagSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create();

        for ($i = 0; $i < 100; $i++) {
            $name = $faker->unique()->word();

            Tag::create([
                'name' => $name,
                'slug' => Str::slug($name) . '-' . substr(Str::uuid(),0,10),
            ]);
        }
    }
}
