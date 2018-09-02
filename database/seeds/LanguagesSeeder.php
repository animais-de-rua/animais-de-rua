<?php

use Illuminate\Database\Seeder;

class LanguagesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('languages')->truncate();

        DB::table('languages')->insert([
            'name' => 'English',
            'flag' => '',
            'abbr' => 'en',
            'script' => 'Latn',
            'native' => 'English',
            'active' => '1',
            'default' => '1'
        ]);

        DB::table('languages')->insert([
            'name' => 'Portuguese',
            'flag' => '',
            'abbr' => 'pt',
            'script' => 'Latn',
            'native' => 'Português',
            'active' => '1',
            'default' => '0'
        ]);
    }
}
