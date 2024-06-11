<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\Review;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\File;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */

     public function run()
     {
         // Path to your JSON file
         $jsonPath = database_path('data/products.json'); // This resolves to <project_root>/database/data/products.json
         $jsonData = File::get($jsonPath);
         $products = json_decode($jsonData, true);
 
         foreach ($products as $productData) {
             // Convert prices to float and handle missing fields
             $price = isset($productData['price']) ? floatval(str_replace(',', '', $productData['price'])) : 0;
             $oldPrice = isset($productData['old_price']) ? floatval(str_replace(',', '', $productData['old_price'])) : 0;
 
             // Create product with default values for missing fields
             $product = Product::create([
                 'title' => $productData['title'] ?? 'Default Title',
                 'description' => $productData['description'] ?? 'Default Description',
                 'price' => $price,
                 'oldprice' => $oldPrice,
                 'images' => json_encode($productData['images'] ?? []),
                 'category_id' => rand(1, 4), // Random category_id
                 'subcategory_id' => rand(1, 4), // Random subcategory_id
                 'magazin_id' => rand(1, 119) // Random magazin_id
             ]);
         }
     }
}
