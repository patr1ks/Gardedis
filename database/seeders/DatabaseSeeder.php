<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            AdminSeeder::class,
            CategorySeeder::class,
            RestaurantSeeder::class,
            EventSeeder::class,
            ContactsSeeder::class,
            MenuSeeder::class,
            RestaurantFormSeeder::class,
        ]);
    }
}