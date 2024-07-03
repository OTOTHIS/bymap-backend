<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            // Main Categories
            [
                'name' => 'Vêtements Hommes',
                'image' => 'https://ma.jumia.is/cms/000_2024/000006_Juin/Revamp_Fashion/5.png',
            ],
            [
                'name' => 'Vêtements Femmes',
                'image' => 'https://ma.jumia.is/cms/000_2024/000006_Juin/Revamp_Fashion/9.png',
            ],
            [
                'name' => 'Chaussures Hommes',
                'image' => 'https://ma.jumia.is/cms/000_2024/000006_Juin/Revamp_Fashion/2.png',
            ],
            [
                'name' => 'Chaussures Femmes',
                'image' => 'https://ma.jumia.is/cms/000_2024/000006_Juin/Revamp_Fashion/2.pngg',
            ],
        ];

        foreach ($categories as $category) {
            Category::create($category);
        }

    }
}
