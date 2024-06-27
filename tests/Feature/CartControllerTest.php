<?php

namespace Tests\Feature;

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class CartControllerTest extends TestCase
{
    use DatabaseTransactions;

    protected $user;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create([
            'password' => \Illuminate\Support\Facades\Hash::make('password')
        ]);

        Sanctum::actingAs($this->user, ['*']);
    }

    /** @test */
    public function it_returns_the_cart_for_authenticated_user()
    {
        $cart = Cart::factory()->create(['user_id' => $this->user->id]);

        $response = $this->getJson('/api/user/carts');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'id', 'user_id', 'cart_items' => [
                    '*' => [
                        'id', 'cart_id', 'product_id', 'quantity', 'taille', 'product' => [
                            'id', 'title', 'price', 'images', 'magazin_id', 'category_id', 'magazins' => [
                                'id', 'name'
                            ], 'category' => [
                                'id', 'name'
                            ]
                        ]
                    ]
                ],
                'totalPrice',
                'totalItems',
                'cartTaxe',
                'total',
            ]);
    }

    /** @test */
    public function it_creates_a_cart_if_non_existent_when_adding_product()
    {
        $product = Product::factory()->create();

        $response = $this->postJson('/api/user/carts', [
            'product_id' => $product->id,
            'quantity' => 2,
            'taille' => 'M',
        ]);

        $response->assertStatus(201)
            ->assertJsonStructure([
                'id', 'product_id', 'quantity', 'taille'
            ]);
    }

    /** @test */
    public function it_adds_the_product_to_existing_cart()
    {
        $cart = Cart::factory()->create(['user_id' => $this->user->id]);
        $product = Product::factory()->create();

        $response = $this->postJson('/api/user/carts', [
            'product_id' => $product->id,
            'quantity' => 2,
            'taille' => 'M',
        ]);

        $response->assertStatus(201)
            ->assertJsonFragment([
                'product_id' => $product->id,
                'quantity' => 2,
                'taille' => 'M',
            ]);
    }
/** @test */
/** @test */
public function it_returns_total_price_correctly()
{
    $cart = Cart::factory()->create(['user_id' => $this->user->id]);
    $product1 = Product::factory()->create(['price' => 10]);
    $product2 = Product::factory()->create(['price' => 20]);
    CartItem::factory()->create(['cart_id' => $cart->id, 'product_id' => $product1->id, 'quantity' => 2]);
    CartItem::factory()->create(['cart_id' => $cart->id, 'product_id' => $product2->id, 'quantity' => 1]);

    $response = $this->getJson('/api/user/carts');

    $totalPrice = (10 * 2) + 20; // 40
    $totalItems = 2 + 1; // 3
    $cartTaxe = round($totalPrice * 0.015, 2); // 0.6 (adjusted based on your CartController logic)
    $total = round($totalPrice + $cartTaxe, 2); // 40.6 (adjusted based on your CartController logic)

    $response->assertStatus(200)
        ->assertJson([
            'totalPrice' => $totalPrice,
            'totalItems' => $totalItems,
            'cartTaxe' => $cartTaxe,
            'total' => $total,
        ]);
}



  

    /** @test */
    public function it_does_not_allow_negative_quantity()
    {
        $product = Product::factory()->create();

        $response = $this->postJson('/api/user/carts', [
            'product_id' => $product->id,
            'quantity' => -1,
            'taille' => 'M',
        ]);

        $response->assertStatus(422);
    }

    /** @test */
    public function it_does_not_allow_zero_quantity()
    {
        $product = Product::factory()->create();

        $response = $this->postJson('/api/user/carts', [
            'product_id' => $product->id,
            'quantity' => 0,
            'taille' => 'M',
        ]);

        $response->assertStatus(422);
    }

    /** @test */
    public function it_allows_different_sizes_in_cart()
    {
        $cart = Cart::factory()->create(['user_id' => $this->user->id]);
        $product = Product::factory()->create();

        $this->postJson('/api/user/carts', [
            'product_id' => $product->id,
            'quantity' => 1,
            'taille' => 'M',
        ])->assertStatus(201);

        $response = $this->postJson('/api/user/carts', [
            'product_id' => $product->id,
            'quantity' => 1,
            'taille' => 'L',
        ]);

        $response->assertStatus(201)
            ->assertJsonFragment([
                'product_id' => $product->id,
                'quantity' => 1,
                'taille' => 'L',
            ]);
    }

    /** @test */
    public function it_does_not_delete_non_existent_product()
    {
        $cartItemId = 999999;

        $response = $this->deleteJson("/api/user/carts/{$cartItemId}");

        $response->assertStatus(404);
    }

    /** @test */
/** @test */



    /** @test */
    public function it_does_not_add_product_with_invalid_id()
    {
        $response = $this->postJson('/api/user/carts', [
            'product_id' => 999999,
            'quantity' => 1,
            'taille' => 'M',
        ]);

        $response->assertStatus(422);
    }

    /** @test */
    public function it_does_not_add_product_without_id()
    {
        $response = $this->postJson('/api/user/carts', [
            'quantity' => 1,
            'taille' => 'M',
        ]);

        $response->assertStatus(422);
    }


    /** @test */
    public function it_adds_product_with_default_quantity()
    {
        $product = Product::factory()->create();

        $response = $this->postJson('/api/user/carts', [
            'product_id' => $product->id,
        ]);

        $response->assertStatus(201)
            ->assertJsonFragment([
                'product_id' => $product->id,
                'quantity' => 1,
            ]);
    }

    /** @test */
    public function it_adds_product_with_custom_quantity()
    {
        $product = Product::factory()->create();

        $response = $this->postJson('/api/user/carts', [
            'product_id' => $product->id,
            'quantity' => 3,
        ]);

        $response->assertStatus(201)
            ->assertJsonFragment([
                'product_id' => $product->id,
                'quantity' => 3,
            ]);
    }

    /** @test */
    public function it_does_not_add_product_with_excessive_quantity()
    {
        $product = Product::factory()->create();

        $response = $this->postJson('/api/user/carts', [
            'product_id' => $product->id,
            'quantity' => 1001,
        ]);

        $response->assertStatus(422);
    }
}
