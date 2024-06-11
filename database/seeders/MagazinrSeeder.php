<?php

namespace Database\Seeders;

use App\Models\Magazin;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;

class MagazinrSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Path to your JSON file
        $jsonPath = database_path('data/magazins.json'); // This resolves to <project_root>/database/data/products.json
        $jsonData = File::get($jsonPath);
        $magazins = json_decode($jsonData, true);

        foreach ($magazins as $magazin) {
                  // Check if photos exist and get the first one if available
                  $imageUrl = 'Image not found';
                  if (isset($magazin['photos']) && count($magazin['photos']) > 0) {
                      $imageUrl = $magazin['photos'][0]['url'];
                  }

            $maagzin = Magazin::create([
                'name' => $magazin['name'] ?? 'Default name',
                'Latitude' => $magazin['location']['lat'] ??'default lat',
                'Longitude' => $magazin['location']['lng'] ??'default lot',
                'image' =>  $imageUrl ,
                'owner_id' => rand(1, 10) // Random magazin_id
            ]);

          
        }
    }
}
