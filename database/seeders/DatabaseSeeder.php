<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        Schema::disableForeignKeyConstraints();

        $this->call([
            PagesSeeder::class,
            SettingsSeeder::class,
            RolesAndPermissionsSeeder::class,
        ]);

        // Faker seeder
        if ($this->command->confirm('Run the fake seeder?')) {
            $this->call(FakeSeeder::class);
        }

        // User seeder
        if ($this->command->confirm('Run the user seeder?')) {
            $this->call(UsersSeeder::class);
        }

        Schema::enableForeignKeyConstraints();
    }
}
