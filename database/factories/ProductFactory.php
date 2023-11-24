<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Product;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */

    protected $model = Product::class;

    public function definition()
    {
        return [
            'name' => fake()->name(),
            'series' => fake()->randomElement(['Spiral-Max', 'Seria Max', 'Raptor']),
            'type' => fake()->randomElement(['wood', 'slab']),
            'distance_min' => fake()->randomNumber(2),
            'distance_max' => fake()->randomNumber(3),
            'distance_code' => fake()->bothify('#??-#####'),
            'photo' => fake()->imageUrl(268, 156),
            'description' => fake()->text(100),
            'short_name' => fake()->bothify('???###?'),
            'height_mm' => fake()->randomNumber(3),
            'height_inch' => fake()->randomNumber(3),
            'packaging' => fake()->randomNumber(3),
            'euro_palet' => fake()->randomNumber(4),
            'price_net' => fake()->randomFloat(1, 5, 60),
        ];
    }
}
