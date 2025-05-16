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
            ['name' => 'Italian Cuisine', 'slug' => 'italian-cuisine'],
            ['name' => 'Vegan Dishes', 'slug' => 'vegan-dishes'],
            ['name' => 'BBQ & Grill', 'slug' => 'bbq-grill'],
            ['name' => 'Fast Food', 'slug' => 'fast-food'],
            ['name' => 'Seafood', 'slug' => 'seafood'],
            ['name' => 'Asian Flavors', 'slug' => 'asian-flavors'],
            ['name' => 'Traditional Latvian', 'slug' => 'traditional-latvian'],
            ['name' => 'Coffee & Desserts', 'slug' => 'coffee-desserts'],
            ['name' => 'Steakhouse', 'slug' => 'steakhouse'],
            ['name' => 'Family Dining', 'slug' => 'family-dining'],
        ];

        foreach ($categories as $category) {
            Category::create($category);
        }
    }
}
