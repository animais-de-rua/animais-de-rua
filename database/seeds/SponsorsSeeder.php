<?php

use Illuminate\Database\Seeder;

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
                'name' => 'Loja do Gato',
                'url' => 'https://www.lojadogato.pt/',
                'image' => 'sponsors/lojadogato.jpg',
            ],
            [
                'name' => 'Mar Shopping',
                'url' => 'https://www.marshopping.com/',
                'image' => 'sponsors/marshopping.jpg',
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
                'name' => 'Loja do Cão',
                'url' => 'https://www.lojadocao.pt/',
                'image' => 'sponsors/lojadocao.jpg',
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
