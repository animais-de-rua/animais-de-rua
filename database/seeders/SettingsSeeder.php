<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SettingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('settings')->truncate();

        $settings = [
            [
                'key' => 'form_contact',
                'name' => 'Formulário de Contacto',
                'description' => 'Endereço relativo ao formulário de contacto.',
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
                'name' => 'Formulário de Voluntariado',
                'description' => 'Endereço relativo ao formulário de voluntariado.',
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
                'name' => 'Formulário de Formação',
                'description' => 'Endereço relativo ao formulário de formação.',
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
                'name' => 'Formulário de Apadrinhamento',
                'description' => 'Endereço relativo ao formulário de apadrinhamento.',
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
                'name' => 'Formulário de Petsitting',
                'description' => 'Endereço relativo ao formulário de petsitting.',
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
                'description' => 'Número base para o contador de intervenções.',
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
        ];

        foreach ($settings as $index => $setting) {
            $result = DB::table('settings')->insert($setting);

            if (!$result) {
                $this->command->info("Insert failed at record $index.");
                return;
            }
        }
    }
}
