<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Banner>
 */
class BannerFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title'=>fake()->title(),
            'redirect_url'=>fake()->url,
            'image'=>fake()->image,
            'content'=>fake()->paragraph(1),
            'order'=>rand(0,5),
            'status'=>rand(0,1),
            'created_at'=>now(),
            'updated_at'=>now()
        ];
    }
}
