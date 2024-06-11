<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Owner>
 */
class OwnerFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'firstname' => fake()->firstName(),
            'lastname' => fake()->lastName(),
            'date_of_birth' => fake()->date(),
            'last_login_date' => fake()->date(),
            'cin' => fake()->jobTitle(),
            'phone' => substr(fake()->phoneNumber(),10),
            'email' =>  fake()->unique()->safeEmail(),
            'password' => '$2y$10$ssjzkveLo5cC10ktCfJgvOtQcKsE0DuRmjijCBciikjPApZRyJHie'
        ];
    }
}
