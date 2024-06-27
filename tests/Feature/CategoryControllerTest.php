<?php
namespace Tests\Feature;

use App\Models\Admin;
use App\Models\Category;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Hash;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class CategoryControllerTest extends TestCase
{
    use DatabaseTransactions, WithFaker;

    public function test_can_list_categories()
    {
       

        // Make a GET request to the index endpoint
        $response = $this->getJson('/api/public/categories');

        // Assert that the response status is 200
        $response->assertStatus(200);

        // Assert that the response contains 3 categories
        $response->assertJsonCount(Category::count());
    }

    public function test_can_create_category()
    {
        // Create an admin user
        $admin = Admin::factory()->create([
            'firstname' => 'Admin',
            'lastname' => 'Admin',
            'email' => 'admin1@admin.admin',
            'password' => Hash::make('123456789'),
            'cin' => fake()->title(),
            'phone' => substr(fake()->phoneNumber(), 10),
        ]);

        // Authenticate as the admin user
        Sanctum::actingAs($admin, ['admin']);

        // Ensure the user is authenticated
        $this->assertAuthenticated();

        // Create a new category
        $data = [
            'name' => $this->faker->word,
        ];

        // Make a POST request to the store endpoint
        $response = $this->postJson('/api/admin/categories', $data);

        // Assert that the response status is 201
        $response->assertStatus(201);

        // Assert that the database contains the new category
        $this->assertDatabaseHas('categories', $data);
    }

    public function test_can_show_category()
    {
        // Create a category
        $category = Category::factory()->create();

        // Make a GET request to the show endpoint
        $response = $this->getJson("/api/public/categories/{$category->id}");

        // Assert that the response status is 200
        $response->assertStatus(200);

        // Assert that the response contains the category data
        $response->assertJson([
            'id' => $category->id,
            'name' => $category->name,
        ]);
    }

    public function test_can_update_category()
    {
        // Create an admin user
        $admin = Admin::factory()->create([
            'firstname' => 'Admin',
            'lastname' => 'Admin',
            'email' => 'admin1@admin.admin',
            'password' => Hash::make('123456789'),
            'cin' => fake()->title(),
            'phone' => substr(fake()->phoneNumber(), 10),
        ]);

        // Authenticate as the admin user
        Sanctum::actingAs($admin, ['admin']);

        // Ensure the user is authenticated
        $this->assertAuthenticated();

        // Create a category
        $category = Category::factory()->create();

        // Data to update the category
        $data = [
            'name' => $this->faker->word,
        ];

        // Make a PUT request to the update endpoint
        $response = $this->putJson("/api/admin/categories/{$category->id}", $data);

        // Assert that the response status is 200
        $response->assertStatus(200);

        // Assert that the database has the updated data
        $this->assertDatabaseHas('categories', $data);
    }


    public function test_can_delete_category()
    {
        // Create an admin user
        $admin = Admin::factory()->create([
            'firstname' => 'Admin',
            'lastname' => 'Admin',
            'email' => 'admin1@admin.admin',
            'password' => Hash::make('123456789'),
            'cin' => fake()->title(),
            'phone' => substr(fake()->phoneNumber(), 10),
        ]);

        // Authenticate as the admin user
        Sanctum::actingAs($admin, ['admin']);

        // Ensure the user is authenticated
        $this->assertAuthenticated();

        // Create a category
        $category = Category::factory()->create();

        // Make a DELETE request to the destroy endpoint
        $response = $this->deleteJson("/api/admin/categories/{$category->id}");

        // Assert that the response status is 204
        $response->assertStatus(204);

        // Assert that the database does not contain the category
        $this->assertDatabaseMissing('categories', ['id' => $category->id]);
    }
}
