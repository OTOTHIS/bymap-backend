<?php

namespace Database\Factories;

use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Review>
 */
class ReviewFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array

    {
           // Récupère tous les IDs des produits existants
    $productIds = Product::all()->pluck('id')->toArray();
    // Récupère tous les IDs des utilisateurs existants
    $userIds = User::all()->pluck('id')->toArray();


        return [
        'user_id' => $this->faker->randomElement($userIds),
        'product_id' => $this->faker->randomElement($productIds),
            'title' => $this->faker->text(200),
            'content' => $this->faker->text(),
            'rating' => $this->faker->numberBetween(1, 5),
        ];
    }
}
