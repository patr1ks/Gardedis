<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Restaurant;
use App\Models\Category;

class RestaurantCategoriesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categoryIds = Category::pluck('id')->toArray();
        $restaurants = Restaurant::all();

        foreach ($restaurants as $restaurant) {
            $count = rand(1, 2);
            $randomCategoryIds = collect($categoryIds)->shuffle()->take($count)->toArray();

            // Eloquent relationship inserts into pivot table
            $restaurant->categories()->syncWithoutDetaching($randomCategoryIds);
        }
    }
}
