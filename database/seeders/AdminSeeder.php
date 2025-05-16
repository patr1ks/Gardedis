<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'name' => 'Admin',
            'email' => 'admin@gmail.com',
            'password' => Hash::make('password'),
            'isAdmin' => 1,
            'isRestaurant' => 0,
        ]);

        // Restaurant users from 1 to 10
        for ($i = 1; $i <= 10; $i++) {
            User::create([
                'name' => 'restaurant' . $i,
                'email' => 'restaurant' . $i . '@gmail.com',
                'password' => Hash::make('password'),
                'isAdmin' => 0,
                'isRestaurant' => 1,
            ]);
        }
    }
}
