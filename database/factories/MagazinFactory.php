<?php

namespace Database\Factories;

use App\Models\Owner;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Magazin>
 */
class MagazinFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {

        $owners = Owner::pluck('id')->toArray();

        return [
            'name' => fake()->word,
          
            'Latitude' => fake()->latitude(),
            'Longitude' => fake()->longitude(),
            'image' => fake()->imageUrl(),
            'owner_id' =>$this->faker->randomElement($owners),
        ];
    }
}
