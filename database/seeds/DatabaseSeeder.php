<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;

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
            PagesSeeder::class,
            CampaignsSeeder::class,
            SponsorsSeeder::class,
            SettingsSeeder::class,
            FakeSeeder::class,
        ]);

        Schema::enableForeignKeyConstraints();
    }
}
