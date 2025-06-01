<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RestaurantFormSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $entries = [
            [
                'name' => 'Wild West BBQ',
                'address' => 'Brīvības iela 145, Rīga',
                'contacts' => 'wwBBQ@gmail.com',
            ],
            [
                'name' => 'Sēņu Stūrītis',
                'address' => 'Lielā iela 12, Liepāja',
                'contacts' => '28341234',
            ],
            [
                'name' => 'Pasta Planet',
                'address' => 'Stacijas iela 8, Jelgava',
                'contacts' => 'pastaplanet@inbox.lv',
            ],
            [
                'name' => 'Gardēžu Nams',
                'address' => 'Kungu iela 5, Rīga',
                'contacts' => '29223311',
            ],
            [
                'name' => 'Burger Bros',
                'address' => 'Krasta iela 27, Jūrmala',
                'contacts' => 'burgerbros@gmail.com',
            ],
            [
                'name' => 'Zivju Zeme',
                'address' => 'Upes iela 1, Ventspils',
                'contacts' => '26784521',
            ],
            [
                'name' => 'Veģetārais Stūrītis',
                'address' => 'Saules iela 18, Valmiera',
                'contacts' => 'vegi@sturis.lv',
            ],
            [
                'name' => 'Brokastu Bufete',
                'address' => 'Tērbatas iela 33, Rīga',
                'contacts' => '28112255',
            ],
            [
                'name' => 'Pica & Pasts',
                'address' => 'Baznīcas iela 7, Cēsis',
                'contacts' => 'picapasts@epasts.lv',
            ],
            [
                'name' => 'Dienvidu Smaržas',
                'address' => 'Rīgas iela 42, Daugavpils',
                'contacts' => '27009988',
            ],
        ];

        DB::table('restaurant_forms')->insert($entries);
    }
}