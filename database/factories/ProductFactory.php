<?php

namespace Database\Factories;

use App\Models\Category;
use App\Models\Magazin;
use App\Models\Subcategory;
use Illuminate\Database\Eloquent\Factories\Factory;

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
    public function definition(): array
    {
        $magazinIds = Magazin::pluck('id')->toArray();

        return [
            'title' => fake()->title(),
            'description' => fake()->text(),
            'price' => fake()->numberBetween(100,600),
            'oldprice' => fake()->numberBetween(600,1000),
            'images' => json_encode(fake()->imageUrl()),
            'category_id' => rand(1,4),
            'magazin_id' => Magazin::factory(), // Ensure this uses the Magazin factory
            'subcategory_id' => rand(1,4),

        ];
    }
}
