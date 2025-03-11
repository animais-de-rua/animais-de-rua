<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SponsorsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('sponsors')->truncate();

        DB::table('sponsors')->insert([
            [
                'name' => 'Hostel HUB',
                'url' => 'https://www.hostelshub.com/',
                'image' => 'sponsors/hostel-hub.jpg',
            ],
            [
                'name' => 'Hotel Porto AS 1829',
                'url' => 'https://as1829.luxhotels.pt/',
                'image' => 'sponsors/hotel-porto-as-1829.jpg',
            ],
            [
                'name' => 'Lisboa Pessoa Hotel',
                'url' => 'https://pessoa.luxhotels.pt/',
                'image' => 'sponsors/hotel-lisbon-pessoa.jpg',
            ],
            [
                'name' => 'Hostel Lisbon Forever',
                'url' => 'http://lisbonforever.pt/',
                'image' => 'sponsors/hostel-lisbon-forever.jpg',
            ],
            [
                'name' => 'Change for animals',
                'url' => 'http://www.changeforanimals.org/',
                'image' => 'sponsors/change.jpg',
            ],
            [
                'name' => 'MIES',
                'url' => 'http://mies.pt',
                'image' => 'sponsors/empree.jpg',
            ],
            [
                'name' => 'Fundação Kangyur Rinpoche',
                'url' => 'http://www.krfportugal.org/',
                'image' => 'sponsors/fundation.jpg',
            ],
            [
                'name' => 'SNIP International',
                'url' => 'http://www.snip-international.org/',
                'image' => 'sponsors/snip.jpg',
            ],
            [
                'name' => 'Rede Expressos',
                'url' => 'http://www.rede-expressos.pt/',
                'image' => 'sponsors/rede.jpg',
            ],
            [
                'name' => 'No brinde',
                'url' => 'http://www.nobrinde.com/',
                'image' => 'sponsors/nobrinde.png',
            ],
            [
                'name' => 'Royal Canin',
                'url' => 'http://www.royalcanin.pt/',
                'image' => 'sponsors/royal_canin.png',
            ],
            [
                'name' => 'Quinta das Águias',
                'url' => 'http://quintadasaguias.org/',
                'image' => 'sponsors/quinta_das_aguias.png',
            ],
            [
                'name' => 'Orgal impressores',
                'url' => 'http://www.orgalimpressores.pt/',
                'image' => 'sponsors/orgal.png',
            ],
            [
                'name' => 'Site fácil',
                'url' => 'https://www.sitefacil.com/',
                'image' => 'sponsors/sitefacil.png',
            ],
            [
                'name' => 'Blue file',
                'url' => 'http://www.bluefile.pt/',
                'image' => 'sponsors/bluefile.png',
            ],
        ]);
    }
}
