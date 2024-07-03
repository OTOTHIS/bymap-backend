<?php

namespace Database\Seeders;

use App\Models\Magazin;
use App\Models\Owner;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;
use Faker\Factory as Faker;

class MagazinrSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    // public function run(): void
    // {
    //     // Path to your JSON file
    //     $jsonPath = database_path('data/magazins.json'); // This resolves to <project_root>/database/data/products.json
    //     $jsonData = File::get($jsonPath);
    //     $magazins = json_decode($jsonData, true);

    //     foreach ($magazins as $magazin) {
    //               // Check if photos exist and get the first one if available
    //               $imageUrl = 'Image not found';
    //               if (isset($magazin['photos']) && count($magazin['photos']) > 0) {
    //                   $imageUrl = $magazin['photos'][0]['url'];
    //               }

    //         $maagzin = Magazin::create([
    //             'name' => $magazin['name'] ?? 'Default name',
    //             'Latitude' => $magazin['location']['lat'] ??'default lat',
    //             'Longitude' => $magazin['location']['lng'] ??'default lot',
    //             'image' =>  $imageUrl ,
    //             'owner_id' => rand(1, 10) // Random magazin_id
    //         ]);

          
    //     }
    // }

    public function run(): void
{
    // Path to your JSON file
    $jsonPath = database_path('data/magazins.json'); // This resolves to <project_root>/database/data/magazins.json
    $jsonData = File::get($jsonPath);
    $magazins = json_decode($jsonData, true);
    $owners = Owner::pluck('id')->toArray();
    $faker = Faker::create();

    foreach ($magazins as $magazin) {
        // Check if necessary data exists
        if (
            isset($magazin['name']) && 
            isset($magazin['location']['lat']) && 
            isset($magazin['location']['lng']) && 
            isset($magazin['photos']) && 
            count($magazin['photos']) > 0
        ) {
            // Get the first photo URL
            $imageUrl = $magazin['photos'][0]['url'];

            // Create the Magazin entry
            Magazin::create([
                'name' => $magazin['name'],
                'Latitude' => $magazin['location']['lat'],
                'Longitude' => $magazin['location']['lng'],
                'image' => $imageUrl,
                'owner_id' => $faker->randomElement($owners) // Random owner_id
            ]);
        }
    }
}

}
