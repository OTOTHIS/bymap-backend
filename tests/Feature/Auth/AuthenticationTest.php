<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class AuthenticationTest extends TestCase
{
    // Ensure to use RefreshDatabase trait if you need database transactions for tests
    // use RefreshDatabase;

    use DatabaseTransactions;
    protected function setUp(): void
    {
        parent::setUp();
    
        // Ensure session and Sanctum middleware are properly handled
        $this->withMiddleware([
            \Illuminate\Session\Middleware\StartSession::class,
            \Laravel\Sanctum\Http\Middleware\EnsureFrontendRequestsAreStateful::class,
        ]);
    }
    

   public function test_users_can_authenticate_using_the_login_screen(): void
    {
        // Create a user using the factory with a hashed password
        $user = User::factory()->create([
            'email' => 'user3@user.com',
            'password' => Hash::make('password'),
        ]);

        // Attempt login
        $response = $this->postJson('/login', [
            'email' => 'user3@user.com',
            'password' => 'password',
        ]);

        // Assert that the user is authenticated
        $response->assertStatus(200); // Adjust based on your application's expected behavior after login
        $this->assertAuthenticatedAs($user);
    }
    


    public function test_users_can_not_authenticate_with_invalid_password(): void
    {
        $user = User::factory()->create();

        $this->post('/login', [
            'email' => $user->email,
            'password' => 'wrong-password',
        ]);

        $this->assertGuest(); // Ensure user is not authenticated
    }

    public function test_users_can_logout(): void
    {
        $user = User::findOrFail(105);

        // Log in the user with specific abilities
        Sanctum::actingAs($user, ['admin', 'owner', 'user'],'web');

        // Ensure the user is authenticated before logout
        $this->assertAuthenticatedAs($user , 'web');

        // Attempt logout
        $response = $this->postJson('/logout');

        // Assert the user is logged out
        $response->assertStatus(401); // Adjust based on your application's expected behavior after logout
    }
}
