<?php

namespace Database\Seeders;

use Backpack\PageManager\app\Models\Page;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class PagesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $date = Carbon::now();

        Page::truncate();

        Page::insert([
            [
                'title' => json_encode([
                    'pt' => 'Home',
                    'en' => 'Home',
                ]),
                'template' => 'home',
                'name' => 'Home',
                'slug' => 'home',
                'extras' => null,
                'extras_translatable' => json_encode([
                    'pt' => ['header' => 'Home'],
                    'en' => ['header' => 'Home'],
                ]),
                'created_at' => $date,
                'updated_at' => $date,
            ],

            [
                'title' => json_encode([
                    'pt' => 'Contactos',
                    'en' => 'Contacts',
                ]),
                'template' => 'contacts',
                'name' => 'Contacts',
                'slug' => 'contacts',
                'extras' => null,
                'extras_translatable' => json_encode([
                    'pt' => ['header' => 'Contactos'],
                    'en' => ['header' => 'Contacts'],
                ]),
                'created_at' => $date,
                'updated_at' => $date,
            ],

            [
                'title' => json_encode([
                    'pt' => 'Política de privacidade',
                    'en' => 'Privacy policy',
                ]),
                'template' => 'privacy',
                'name' => 'Política de privacidade',
                'slug' => 'privacy-policy',
                'extras' => null,
                'extras_translatable' => json_encode([
                    'pt' => ['header' => 'Política de privacidade'],
                    'en' => ['header' => 'Privacy Policy'],
                ]),
                'created_at' => $date,
                'updated_at' => $date,
            ],

            [
                'title' => json_encode([
                    'pt' => 'Termos e Condições',
                    'en' => 'Terms and conditions',
                ]),
                'template' => 'terms',
                'name' => 'Termos e Condições',
                'slug' => 'terms-and-condicions',
                'extras' => null,
                'extras_translatable' => json_encode([
                    'pt' => ['header' => 'Termos e Condições'],
                    'en' => ['header' => 'Terms and Conditions'],
                ]),
                'created_at' => $date,
                'updated_at' => $date,
            ],

        ]);
    }
}
