<?php
namespace Tests\Feature;

use App\Models\Admin;
use App\Models\Owner;
use App\Models\Magazin;
use App\Models\Product;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Hash;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class MagazinControllerTest extends TestCase
{
    use DatabaseTransactions, WithFaker;

    protected function setUp(): void
    {
        parent::setUp();
        $this->withMiddleware([
            \Illuminate\Session\Middleware\StartSession::class,
            \Laravel\Sanctum\Http\Middleware\EnsureFrontendRequestsAreStateful::class,
        ]);
    }

    public function test_can_list_magazins()
    {
        $owner = Owner::factory()->create();
        $magazins = Magazin::factory()->count(3)->create(['owner_id' => $owner->id]);

        Sanctum::actingAs($owner, ['owner']);

        $response = $this->getJson('/api/owner/magazins');

        $response->assertStatus(200);
        $response->assertJsonCount(3);
    }

    public function test_can_create_magazin()
    {
        $owner = Owner::factory()->create();

        Sanctum::actingAs($owner, ['owner']);

        $data = [
            'name' => 'Test Magazin',
            'adresse' => '123 Test St',
            'Latitude' => '123.456',
            'Longitude' => '456.789',
            'image' => 'https://cdn.edi-static.fr/image/upload/c_scale,h_679/c_crop,w_1300/f_auto,q_auto/v1/Img/BREVE/2023/6/382931/Boutique-Zara-F.jpg', // Handle image upload in a separate test
        ];

        $response = $this->postJson('/api/owner/magazins', $data);

        $response->assertStatus(200);
        // $this->assertDatabaseHas('magazins', ['name' => 'Test Magazin']);
    }

    public function test_can_show_magazin()
    {
        $owner = Owner::factory()->create();
        $magazin = Magazin::factory()->create(['owner_id' => $owner->id]);

        Sanctum::actingAs($owner, ['owner']);

        $response = $this->getJson("/api/owner/magazins/{$magazin->id}");

        $response->assertStatus(200);
        $response->assertJson(['name' => $magazin->name]);
    }

    public function test_can_update_magazin()
    {
        // Assume an owner exists in the database
        $owner = Owner::factory()->create();

        // Create a magazin associated with the owner
        $magazin = Magazin::factory()->create(['owner_id' => $owner->id]);

        // Authenticate as the owner user
        Sanctum::actingAs($owner, ['owner']);

        // Ensure the user is authenticated
        $this->assertAuthenticated();

        // Find the magazin created earlier
        $foundMagazin = Magazin::where('owner_id', $owner->id)->first();

        // Data to update the magazin
        $data = [
            'name' => 'Updated Magazin',
            'adresse' => 'Updated Adresse',
            'Latitude' => '654.321',
            'Longitude' => '987.654',
            'owner_id' => $owner->id,
        ];

        // Make a PUT request to the update endpoint
        $response = $this->putJson("/api/owner/magazins/{$foundMagazin->id}", $data);

        // Assert that the response status is 200
        $response->assertStatus(200);

        // Assert that the database has the updated data
        $this->assertDatabaseHas('magazins', ['name' => 'Updated Magazin']);
    }

    public function test_can_delete_magazin()
    {
        // Create an owner user
        $owner = Owner::factory()->create();

        // Create a magazin associated with the owner
        $magazin = Magazin::factory()->create(['owner_id' => $owner->id]);

        // Authenticate as the owner user
        Sanctum::actingAs($owner, ['owner']);

        // Ensure the user is authenticated
        $this->assertAuthenticated();

        // Find the magazin created earlier
        $foundMagazin = Magazin::where('owner_id', $owner->id)->first();

        // Make a DELETE request to the destroy endpoint
        $response = $this->deleteJson("/api/owner/magazins/{$foundMagazin->id}");

        // Assert that the response status is 204
        $response->assertStatus(200);
    }



    public function test_can_paginate_magazins()
    {

        $response = $this->getJson('/api/public/magazinas?paginate=2');

        $response->assertStatus(200);
        $response->assertJsonCount(2, 'data');
    }
}
