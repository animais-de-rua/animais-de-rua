<?php

namespace Database\Seeders;

use Backpack\Settings\app\Models\Setting;
use Illuminate\Database\Seeder;

class SettingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Setting::truncate();

        Setting::insert([
            [
                'key' => 'form_contact',
                'name' => 'Formul�rio de Contacto',
                'description' => 'Endere�o relativo ao formul�rio de contacto.',
                'value' => 'geral@animaisderua.org',
                'field' => json_encode([
                    'name' => 'value',
                    'label' => 'Email',
                    'type' => 'email',
                ]),
                'active' => 1,
            ],
            [
                'key' => 'form_volunteer',
                'name' => 'Formul�rio de Voluntariado',
                'description' => 'Endere�o relativo ao formul�rio de voluntariado.',
                'value' => 'voluntarios@animaisderua.org',
                'field' => json_encode([
                    'name' => 'value',
                    'label' => 'Email',
                    'type' => 'email',
                ]),
                'active' => 1,
            ],
            [
                'key' => 'form_training',
                'name' => 'Formul�rio de Forma��o',
                'description' => 'Endere�o relativo ao formul�rio de forma��o.',
                'value' => 'formacaoCED@animaisderua.org',
                'field' => json_encode([
                    'name' => 'value',
                    'label' => 'Email',
                    'type' => 'email',
                ]),
                'active' => 1,
            ],
            [
                'key' => 'form_godfather',
                'name' => 'Formul�rio de Apadrinhamento',
                'description' => 'Endere�o relativo ao formul�rio de apadrinhamento.',
                'value' => 'geral@animaisderua.org',
                'field' => json_encode([
                    'name' => 'value',
                    'label' => 'Email',
                    'type' => 'email',
                ]),
                'active' => 1,
            ],
            [
                'key' => 'form_petsitting',
                'name' => 'Formul�rio de Petsitting',
                'description' => 'Endere�o relativo ao formul�rio de petsitting.',
                'value' => 'petsitting@animaisderua.org',
                'field' => json_encode([
                    'name' => 'value',
                    'label' => 'Email',
                    'type' => 'email',
                ]),
                'active' => 1,
            ],
            [
                'key' => 'base_counter',
                'name' => 'Contador Base',
                'description' => 'N�mero base para o contador de interven��es.',
                'value' => '26000',
                'field' => json_encode([
                    'name' => 'value',
                    'label' => 'Valor',
                    'type' => 'number',
                ]),
                'active' => 1,
            ],
            [
                'key' => 'vat',
                'name' => 'IVA',
                'description' => 'Valor do IVA.',
                'value' => '23',
                'field' => json_encode([
                    'name' => 'value',
                    'label' => 'Valor',
                    'type' => 'number',
                ]),
                'active' => 1,
            ],
        ]);
    }
}
