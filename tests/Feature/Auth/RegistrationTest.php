<?php

namespace Tests\Feature\Auth;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Testing\Fakes\Fake;
use Tests\TestCase;
use Faker\Factory as Faker;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class RegistrationTest extends TestCase
{
    use DatabaseTransactions;

    public function test_new_users_can_register(): void
    {
        $faker = Faker::create();

        $response = $this->postJson('/register', [
            'firstname' => $faker->firstName,
            'lastname' => $faker->lastName,
            'tel' => $faker->phoneNumber,
            'email' => $faker->unique()->safeEmail,
            'password' => 'password',
            'password_confirmation' => 'password',
        ]);

        $response->assertStatus(201); // Adjust based on your application's expected behavior
        $this->assertDatabaseHas('users', [
            'email' => $response->json('email'),
        ]);
    }
}
