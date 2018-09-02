<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Schema::disableForeignKeyConstraints();

        $this->call([
            TerritoriesSeeder::class,
            LanguagesSeeder::class,
            RolesAndPermissionsSeeder::class,
            UsersSeeder::class,
            HeadquartersSeeder::class,
            TreatmentTypesSeeder::class,
            FriendCardModalitiesSeeder::class,
            PartnersSeeder::class,
            FakeSeeder::class
        ]);

        Schema::enableForeignKeyConstraints();
    }
}
