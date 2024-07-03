<?php
namespace Tests\Feature;

use App\Models\Admin;
use App\Models\Owner;
use App\Models\Magazin;
use App\Models\Category;
use App\Models\Subcategory;
use App\Models\Product;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class ProductControllerTest extends TestCase
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

    public function test_can_list_products()
    {
        $owner = Owner::factory()->create();
        $magazin = Magazin::factory()->create(['owner_id' => $owner->id]);
        Product::factory()->count(3)->create(['magazin_id' => $magazin->id]);

        Sanctum::actingAs($owner, ['owner']);

        $response = $this->getJson('/api/owner/products');

        $response->assertStatus(200);
        $response->assertJsonCount(20, 'data');
    }

    public function test_can_create_product()
    {
        $owner = Owner::factory()->create();
        $magazin = Magazin::factory()->create(['owner_id' => $owner->id]);
        $category = Category::factory()->create();
        $subcategory = Subcategory::factory()->create();

        Sanctum::actingAs($owner, ['owner']);

        Storage::fake('local');
        $file = UploadedFile::fake()->image('product.jpg');

        $data = [
            'title' => 'Test Product',
            'description' => 'This is a test product.',
            'price' => 99.99,
            'oldprice'=>300,
            'magazin_id' => $magazin->id,
            'category_id' => $category->id,
            'subcategory_id' => $subcategory->id,
            'images' => json_encode([$file->hashName()]), // Correctly format the images field as a JSON array
        ];

        $response = $this->postJson('/api/owner/products', $data);

        $response->assertStatus(201);
        $this->assertDatabaseHas('products', ['title' => 'Test Product']);
    }

    public function test_can_show_product()
    {
        $owner = Owner::factory()->create();
        $magazin = Magazin::factory()->create(['owner_id' => $owner->id]);
        $product = Product::factory()->create(['magazin_id' => $magazin->id]);

        Sanctum::actingAs($owner, ['owner']);

        $response = $this->getJson("/api/owner/products/{$product->id}");

        $response->assertStatus(200);
        $response->assertJson(['title' => $product->title]);
    }

    public function test_can_update_product()
    {
        $owner = Owner::factory()->create();
        $magazin = Magazin::factory()->create(['owner_id' => $owner->id]);
        $product = Product::factory()->create(['magazin_id' => $magazin->id]);
        $category = Category::factory()->create();

        Sanctum::actingAs($owner, ['owner']);

        $data = [
            'title' => 'Updated Product',
            'price' => 199.99,
            'category_id' => $category->id,
        ];

        $response = $this->putJson("/api/owner/products/{$product->id}", $data);

        $response->assertStatus(200);
        $this->assertDatabaseHas('products', ['title' => 'Updated Product']);
    }

    public function test_can_delete_product()
    {
        $owner = Owner::factory()->create();
        $magazin = Magazin::factory()->create(['owner_id' => $owner->id]);
        $product = Product::factory()->create(['magazin_id' => $magazin->id]);

        Sanctum::actingAs($owner, ['owner']);

        $response = $this->deleteJson("/api/owner/products/{$product->id}");

        $response->assertStatus(200);
    }
}
