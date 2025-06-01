<?php

namespace Database\Seeders;

use App\Models\Category;
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
            ['name' => 'Breakfast & Brunch', 'slug' => 'breakfast-brunch'],
            ['name' => 'Gourmet & Fine Dining', 'slug' => 'gourmet-fine-dining'],
            ['name' => 'Mexican Cuisine', 'slug' => 'mexican-cuisine'],
            ['name' => 'Middle Eastern', 'slug' => 'middle-eastern'],
            ['name' => 'Street Food', 'slug' => 'street-food'],
            ['name' => 'Vegetarian Options', 'slug' => 'vegetarian-options'],
            ['name' => 'Bakeries & Pastry', 'slug' => 'bakeries-pastry'],
            ['name' => 'Healthy Choices', 'slug' => 'healthy-choices'],
            ['name' => 'Burger Joints', 'slug' => 'burger-joints'],
            ['name' => 'Tapas & Small Plates', 'slug' => 'tapas-small-plates'],
        ];

        foreach ($categories as $category) {
            Category::create($category);
        }
    }
}