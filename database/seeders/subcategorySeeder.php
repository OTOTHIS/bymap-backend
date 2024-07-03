<?php

namespace Database\Seeders;

use App\Models\Subcategory;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class subcategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $subcategories = [
            // Men's Clothing Subcategories
            [
                'name' => 'T-shirts Hommes',
                'image' => 'https://ma.jumia.is/cms/000_2022/Z-Categories/3-Mode/32-ModeH/321-VetH/220/T-Shirts__DÃbardeurs___Polos-removebg-preview.png',
            ],
            [
                'name' => 'Jeans Hommes',
                'image' => 'https://ma.jumia.is/cms/000_2022/Z-Categories/3-Mode/32-ModeH/321-VetH/220/Jeans-removebg-preview.png',
            ],
            [
                'name' => 'Vestes Hommes',
                'image' => 'https://ma.jumia.is/cms/000_2022/Z-Categories/3-Mode/32-ModeH/321-VetH/220/Smokings___Costumes-removebg-preview.png',
            ],
            [
                'name' => 'Chemises Hommes',
                'image' => 'https://ma.jumia.is/cms/000_2022/Z-Categories/3-Mode/32-ModeH/321-VetH/OrP/220/Chemises-removebg-preview.png',
            ],
            [
                'name' => 'Shorts Hommes',
                'image' => 'https://ma.jumia.is/cms/000_2022/Z-Categories/3-Mode/32-ModeH/321-VetH/220/Shorts-removebg-preview.png',
            ],
            [
                'name' => 'Costumes Hommes',
                'image' => 'https://ma.jumia.is/cms/000_2022/Z-Categories/3-Mode/32-ModeH/321-VetH/220/Pull___Gilets-removebg-preview.png',
            ],
            // Women's Clothing Subcategories
            [
                'name' => 'T-shirts Femmes',
                'image' => 'https://ma.jumia.is/unsafe/fit-in/500x500/filters:fill(white)/product/30/372056/1.jpg?8626',
            ],
            [
                'name' => 'Jeans Femmes',
                'image' => 'https://ma.jumia.is/cms/000_2022/Z-Categories/3-Mode/31-ModeF/311-VetF/220/Jeans-removebg-preview-001.png',
            ],
            [
                'name' => 'Vestes Femmes',
                'image' => 'https://ma.jumia.is/cms/000_2022/Z-Categories/3-Mode/31-ModeF/311-VetF/220/Manteaux___veste-removebg-preview.png',
            ],
            [
                'name' => 'Robes Femmes',
                'image' => 'https://ma.jumia.is/cms/000_2022/Z-Categories/3-Mode/31-ModeF/311-VetF/220/Robes-removebg-preview.png',
            ],
            [
                'name' => 'Jupes Femmes',
                'image' => 'https://ma.jumia.is/cms/000_2022/Z-Categories/3-Mode/31-ModeF/311-VetF/220/Jupes-removebg-preview.png',
            ],
           
            // Men's Shoes Subcategories
            [
                'name' => 'Chaussures de sport Hommes',
                'image' => 'https://ma.jumia.is/cms/000_2022/Z-Categories/3-Mode/32-ModeH/322-ChaussH/220/Espadrilles-removebg-preview.png',
            ],
            [
                'name' => 'Chaussures habillées Hommes',
                'image' => 'https://ma.jumia.is/cms/000_2022/Z-Categories/3-Mode/32-ModeH/322-ChaussH/220/Mocassin-removebg-preview.png',
            ],
            [
                'name' => 'Bottes Hommes',
                'image' => 'https://ma.jumia.is/cms/000_2022/Z-Categories/3-Mode/32-ModeH/322-ChaussH/220/Bottes-removebg-preview.png',
            ],
            // Women's Shoes Subcategories
            [
                'name' => 'Chaussures de sport Femmes',
                'image' => 'https://ma.jumia.is/cms/000_2022/Z-Categories/3-Mode/31-ModeF/312-ChaussF/220/Baskets-removebg-preview-001.png',
            ],
            [
                'name' => 'Chaussures habillées Femmes',
                'image' => 'https://ma.jumia.is/cms/000_2022/Z-Categories/3-Mode/31-ModeF/312-ChaussF/220/Mocassins-removebg-preview.png',
            ],
            [
                'name' => 'Bottes Femmes',
                'image' => 'https://ma.jumia.is/cms/000_2022/Z-Categories/3-Mode/31-ModeF/312-ChaussF/220/Bottes___Bottines-removebg-preview.png',
            ],
        ];

        foreach ($subcategories as $subcategory) {
            Subcategory::create($subcategory);
        }

    }
}
