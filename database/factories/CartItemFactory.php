<?php

namespace Database\Factories;

use App\Models\Cart;
use App\Models\Magazin;
use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;
use Faker\Factory as Faker;


/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\CartItem>
 */
class CartItemFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $carts = Cart::pluck('id')->toArray();
        $products = Product::pluck('id')->toArray();

        $faker = Faker::create();
        return [
            'cart_id' =>  $faker->randomElement($carts),
            'product_id' => $faker->randomElement($products),
            'quantity' => $this->faker->numberBetween(1, 5),
        ];
    }
}
