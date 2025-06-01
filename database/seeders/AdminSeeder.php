<?php

namespace Database\Seeders;

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
        // Admin account
        User::create([
            'name' => 'admin',
            'email' => 'admin@gmail.com',
            'password' => Hash::make('password'),
            'isAdmin' => 1,
            'isRestaurant' => 0,
        ]);

        // Restaurant-themed usernames
        $owners = [
            ['name' => 'sunsetgrill', 'email' => 'sunset@grill.com'],
            ['name' => 'urbanbites', 'email' => 'urban@bites.com'],
            ['name' => 'forestfeast', 'email' => 'forest@feast.com'],
            ['name' => 'bellapasta', 'email' => 'bella@pasta.com'],
            ['name' => 'oceanbreeze', 'email' => 'ocean@breeze.com'],
            ['name' => 'mountaindine', 'email' => 'mountain@dine.com'],
            ['name' => 'spiceroute', 'email' => 'spice@route.com'],
            ['name' => 'greenfork', 'email' => 'green@fork.com'],
            ['name' => 'metrodeli', 'email' => 'metro@deli.com'],
            ['name' => 'familytable', 'email' => 'family@table.com'],
        ];

        foreach ($owners as $owner) {
            User::create([
                'name' => $owner['name'],
                'email' => $owner['email'],
                'password' => Hash::make('password'),
                'isAdmin' => 0,
                'isRestaurant' => 1,
            ]);
        }

        // Additional regular users
        $users = [
            ['name' => 'janisk', 'email' => 'janis@example.com'],
            ['name' => 'laural', 'email' => 'laura@example.com'],
            ['name' => 'peterisp', 'email' => 'peteris@example.com'],
            ['name' => 'anitaoz', 'email' => 'anita@example.com'],
        ];

        foreach ($users as $user) {
            User::create([
                'name' => $user['name'],
                'email' => $user['email'],
                'password' => Hash::make('password'),
                'isAdmin' => 0,
                'isRestaurant' => 0,
            ]);
        }
    }
}