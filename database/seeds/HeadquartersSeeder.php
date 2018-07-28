<?php

use Illuminate\Database\Seeder;
use Carbon\Carbon;

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

        $date = Carbon::now();

        DB::table('headquarters')->insert([
        [
            'name' => 'Porto',
            'created_at' => $date,
            'updated_at' => $date,
        ],[
            'name' => 'Lisboa',
            'created_at' => $date,
            'updated_at' => $date,
        ],[
            'name' => 'Sintra',
            'created_at' => $date,
            'updated_at' => $date,
        ],[
            'name' => 'Faro',
            'created_at' => $date,
            'updated_at' => $date,
        ],[
            'name' => 'São Miguel',
            'created_at' => $date,
            'updated_at' => $date,
        ]]);

        DB::table('headquarters_territories')->insert([

        // Porto
        [
            'headquarter_id' => 1,
            'territory_id'   => '1312', // Porto
        ],[
            'headquarter_id' => 1,
            'territory_id'   => '1317', // Gaia
        ],[
            'headquarter_id' => 1,
            'territory_id'   => '1306', // Maia
        ],[
            'headquarter_id' => 1,
            'territory_id'   => '1308', // Matosinhos
        ],[
            'headquarter_id' => 1,
            'territory_id'   => '1315', // Valongo
        ],[
            'headquarter_id' => 1,
            'territory_id'   => '1304', // Gondomar
        ],

        // Lisboa
        [
            'headquarter_id' => 2,
            'territory_id'   => '1106', // Lisboa
        ],
        [
            'headquarter_id' => 2,
            'territory_id'   => '1510', // Seixal
        ],
        [
            'headquarter_id' => 2,
            'territory_id'   => '150303', // Costa da Caparica
        ],
        [
            'headquarter_id' => 2,
            'territory_id'   => '1114', // Vila Franca de Xira
        ],

        // Sintra
        [
            'headquarter_id' => 3,
            'territory_id'   => '1111', // Sintra
        ],
        [
            'headquarter_id' => 3,
            'territory_id'   => '1110', // Oeiras
        ],
        [
            'headquarter_id' => 3,
            'territory_id'   => '1105', // Cascais
        ],

        // Faro
        [
            'headquarter_id' => 4,
            'territory_id'   => '0805', // Faro
        ],
        [
            'headquarter_id' => 4,
            'territory_id'   => '0809', // Monchique
        ],

        // Sao Miguel
        [
            'headquarter_id' => 5,
            'territory_id'   => '21', // Ilha São Miguel
        ]]);
    }
}
