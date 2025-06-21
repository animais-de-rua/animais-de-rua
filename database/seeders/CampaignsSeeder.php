<?php

namespace Database\Seeders;

use App\Models\Campaign;
use Illuminate\Database\Seeder;

class CampaignsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Campaign::truncate();

        Campaign::insert([
            [
                'name' => json_encode([
                    'pt' => 'Projecto Educativo',
                ]),
                'introduction' => json_encode([
                    'pt' => 'Desenvolvemos um projecto educativo escolar com o objectivo de transmitir aos mais novos os valores da compaixão e respeito pela vida animal.',
                ]),
                'description' => json_encode([
                    'pt' => '',
                ]),
                'image' => 'campaigns/projecto-educativo.jpg',
            ],
            [
                'name' => json_encode([
                    'pt' => 'Parceria AR+CA',
                ]),
                'introduction' => json_encode([
                    'pt' => 'As famílias carenciadas e sem-abrigo têm vindo a aumentar exponencialmente em Portugal e as associações de solidariedade assistem muito de perto a esta luta num esforço constante para responder, pelo menos, às necessidades básicas destas famílias.',
                ]),
                'description' => json_encode([
                    'pt' => '',
                ]),
                'image' => 'campaigns/ar-ca.jpg',
            ],
            [
                'name' => json_encode([
                    'pt' => 'Projecto da Praia de Faro',
                ]),
                'introduction' => json_encode([
                    'pt' => 'Em parceria com a Change For Animals Foundation, a Dogs Trust, a Universidade Lusófona e a Câmara Municipal de Faro, desenvolvemos na Praia de Faro um projecto comunitário pioneiro a nível mundial, com vista à melhoria da qualidade de vida dos animais e sua relação com os moradores.',
                ]),
                'description' => json_encode([
                    'pt' => '',
                ]),
                'image' => 'campaigns/praia-faro.jpg',
            ],
        ]);
    }
}
