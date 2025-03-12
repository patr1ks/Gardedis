<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Restaurant;

class RestaurantSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Restaurant::create([
            'title' => 'Restorāns 1',
            'description' => 'Restorāns 1 apraksts',
            'published' => 1,
            'price' => 10.00,
            'category_id' => 1, 
            ]);
    }
}
