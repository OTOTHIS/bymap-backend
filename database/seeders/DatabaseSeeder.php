<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Admin;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Category;
use App\Models\Magazin;
use App\Models\Owner;
use App\Models\Product;
use App\Models\Review;
use App\Models\Subcategory;
use App\Models\User;
use Database\Factories\SubcategoryFactory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();
        // Owner::factory(10)->create();
       

   
        // $this->call(ProductSeeder::class);
        // $this->call(MagazinrSeeder::class);


            // Magazin::factory(10)->create();
            // category::factory(4)->create();
            // Subcategory::factory(4)->create();
            // $this->call(ProductSeeder::class);

            // // Product::factory(4)->create();
            // Cart::factory(4)->create();
            // CartItem::factory(10)->create();
            // Review::factory(1000)->create(); // Generate 100 reviews



            
            // \App\Models\Order::factory(3)->create();
            // \App\Models\OrderItem::factory(3)->create();

        // User::factory()->create([
        //     'firstname' => 'user',
        //     'lastname' => 'user',
        //     'email' => 'user@user.com',
        //     'tel' => '0343242434243',
        //     'password' => '123456789'
        // ]);

        Admin::factory()->create([
            'firstname' => 'Admin',
            'lastname' => 'Admin',
            'cin' => fake()->title(),
            'phone' => substr(fake()->phoneNumber(),10),
            'email' => 'admin@admin.admin',
            'password' => Hash::make('123456789'),
]);

        // Owner::factory()->create([
        //     'firstname' => 'Owner',
        //     'lastname' => 'Owner',
        //     'date_of_birth' => fake()->date(),
        //     'last_login_date' => fake()->date(),
        //     'cin' => fake()->title(),
        //     'phone' => substr(fake()->phoneNumber(),10),
        //     'email' => 'Owner@Owner.Owner',
        //     'password' => '$2y$10$ssjzkveLo5cC10ktCfJgvOtQcKsE0DuRmjijCBciikjPApZRyJHie'
        // ]);
    //     Owner::factory()->create([
    //         'firstname' => 'othmane',
    //         'lastname' => 'assadi',
    //         'date_of_birth' => fake()->date(),
    //         'last_login_date' => fake()->date(),
    //         'cin' => fake()->title(),
    //         'phone' => substr(fake()->phoneNumber(),10),
    //         'email' => 'othmane@gmail.com',
    //         'password' => Hash::make('123456789'),
    // ]);


    }
}
