<?php

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
