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
        Category::create(['name' => 'Jūras veltes', 'slug' => 'seafood']);
        Category::create(['name' => 'Veģetārais', 'slug' => 'vegetarian']);
        Category::create(['name' => 'Vegānais', 'slug' => 'vegan']);
        Category::create(['name' => 'Kafija un deserti', 'slug' => 'coffee-desserts']);
        Category::create(['name' => 'Ģimenes restorāni', 'slug' => 'family-friendly']);
        Category::create(['name' => 'Augstas klases restorāni', 'slug' => 'fine-dining']);
        Category::create(['name' => 'Brunch un brokastis', 'slug' => 'brunch-breakfast']);
        Category::create(['name' => 'Sushi un Japānas virtuve', 'slug' => 'sushi-japanese']); 
    }
}
