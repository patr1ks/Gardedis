<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ContactsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $contacts = [
            [
                'first_name' => 'Anna',
                'last_name' => 'Kalniņa',
                'email' => 'anna.kalnina@example.com',
                'telephone' => '20000001',
                'message' => 'Labdien! Mani interesē, kā šī platforma darbojas un vai varu veikt rezervāciju bez reģistrācijas? Paldies jau iepriekš!',
            ],
            [
                'first_name' => 'Jānis',
                'last_name' => 'Ozoliņš',
                'email' => 'janis.ozolins@example.com',
                'telephone' => '20000002',
                'message' => 'Sveiki! Vai jūs varētu pievienot manu iecienīto restorānu pieejamo vietu sarakstā? Tas būtu ļoti noderīgi.',
            ],
            [
                'first_name' => 'Laura',
                'last_name' => 'Liepa',
                'email' => 'laura.liepa@example.com',
                'telephone' => '20000003',
                'message' => 'Liels paldies par ērtu platformu! Vai tuvākajā laikā plānojat arī iOS lietotni?',
            ],
            [
                'first_name' => 'Mārtiņš',
                'last_name' => 'Bērziņš',
                'email' => 'martins.berzins@example.com',
                'telephone' => '20000004',
                'message' => 'Man radās problēma ar rezervācijas apstiprinājumu. Vai varat, lūdzu, pārbaudīt, vai viss ir kārtībā?',
            ],
            [
                'first_name' => 'Elīna',
                'last_name' => 'Strazdiņa',
                'email' => 'elina.strazdina@example.com',
                'telephone' => '20000005',
                'message' => 'Vai ir iespēja kā restorāna īpašniekam pievienot savu restorānu? Gribētu uzzināt, kā tas notiek.',
            ],
            [
                'first_name' => 'Roberts',
                'last_name' => 'Pētersons',
                'email' => 'roberts.p@example.com',
                'telephone' => '20000006',
                'message' => 'Labdien! Vai lietotāji var vērtēt restorānus? Esmu atstājis atsauksmi, bet tā neparādās.',
            ],
            [
                'first_name' => 'Kristīne',
                'last_name' => 'Vītola',
                'email' => 'kristine.v@example.com',
                'telephone' => '20000007',
                'message' => 'Sveiki! Vēlējos pateikties par lielisko lietotāju pieredzi. Viss strādā ļoti raiti un saprotami.',
            ],
            [
                'first_name' => 'Edgars',
                'last_name' => 'Zariņš',
                'email' => 'edgars.zarins@example.com',
                'telephone' => '20000008',
                'message' => 'Vai platforma piedāvā kādas akcijas vai lojalitātes programmas lietotājiem? Būtu forši redzēt atlaižu iespējas.',
            ],
            [
                'first_name' => 'Līga',
                'last_name' => 'Andersone',
                'email' => 'liga.andersone@example.com',
                'telephone' => '20000009',
                'message' => 'Labdien! Vai iespējams mainīt rezervācijas laiku pēc tās veikšanas? Nevarēju atrast šo opciju.',
            ],
            [
                'first_name' => 'Artūrs',
                'last_name' => 'Grīnbergs',
                'email' => 'arturs.g@example.com',
                'telephone' => '20000010',
                'message' => 'Man ir priekšlikums par jaunu funkciju, kas varētu uzlabot rezervāciju pārvaldību. Kur varu nosūtīt detalizētu aprakstu?',
            ],
        ];

        DB::table('contacts')->insert($contacts);
    }
}
