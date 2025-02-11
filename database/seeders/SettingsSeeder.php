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
                'key' => 'contact',
                'name' => 'Contact',
                'description' => '',
                'value' => 'geral@mail.com',
                'field' => json_encode([
                    'name' => 'value',
                    'label' => 'Value',
                    'type' => 'email',
                ]),
                'active' => 1,
            ],
        ]);
    }
}
