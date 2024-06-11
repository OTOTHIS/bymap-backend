<?php

namespace Database\Factories;

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
        return [
            'name' => fake()->word,
          
            'Latitude' => fake()->latitude(),
            'Longitude' => fake()->longitude(),
            'image' => fake()->imageUrl(),
            'owner_id' => function () {
                // Assuming you have an Owner model and you want to associate the Magazin with an existing owner
                return \App\Models\Owner::factory()->create()->id;
            },
        ];
    }
}
