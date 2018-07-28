<?php

use Illuminate\Database\Seeder;
use Carbon\Carbon;

class UsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->truncate();

        DB::table('users')->insert([
            'name' => 'António Almeida',
            'email' => 'promatik@gmail.com',
            'password' => '$2y$10$ZoyR5TtV3.V.QCMvYVRL2.LFaO13PGl50Bfxxz629zY.fY8BzoIfe',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);
    }
}
