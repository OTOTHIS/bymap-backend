<?php

namespace Database\Factories;

use App\Models\Magazin;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Order>
 */
class OrderFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
  
            // $owner = auth()->user();
            $magazinIds = Magazin::where('owner_id', 1)->pluck('id')->toArray();
    
            // Define order statuses
            $statuses = ['pending', 'processing', 'shipped', 'delivered', 'cancelled'];
    
            return [
                'user_id' =>1,
                'magazin_id' => $this->faker->randomElement($magazinIds), // Random Magazin ID for the authenticated owner
                'status' => $this->faker->randomElement($statuses),
            ];
        }
        
}

