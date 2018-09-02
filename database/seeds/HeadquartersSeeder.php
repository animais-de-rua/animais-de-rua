<?php

use Carbon\Carbon;
use Illuminate\Database\Seeder;

class HeadquartersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('headquarters')->truncate();
        DB::table('headquarters_territories')->truncate();

        $i = 0;
        $date = Carbon::now();

        $headquarters = [
            'Porto' => [
                '1312', // Porto
                '1317', // Gaia
                '1306', // Maia
                '1308', // Matosinhos
                '1315', // Valongo
                '1304' // Gondomar
            ],
            'Lisboa' => [
                '1106', // Lisboa
                '1510', // Seixal
                '150303', // Costa da Caparica
                '1114' // Vila Franca de Xira
            ],
            'Sintra' => [
                '1111', // Sintra
                '1110', // Oeiras
                '1105', // Cascais
                '0805' // Faro
            ],
            'Faro' => [
                '0809' // Monchique
            ],
            'São Miguel' => [
                '21' // Ilha São Miguel
            ]
        ];

        // Headquarters
        foreach ($headquarters as $headquarter => $territories) {
            DB::table('headquarters')->insert(['created_at' => $date, 'updated_at' => $date, 'name' => $headquarter]);
            $i++;

            // Headquarter Territories
            foreach ($territories as $territory) {
                DB::table('headquarters_territories')->insert([
                    'headquarter_id' => $i,
                    'territory_id' => $territory
                ]);
            }
        }
    }
}
