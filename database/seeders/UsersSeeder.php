<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

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
        DB::table('user_has_roles')->truncate();
        DB::table('user_has_permissions')->truncate();

        DB::table('users')->insert([
            [
                'name' => 'António Almeida',
                'email' => 'promatik@gmail.com',
                'password' => '$2y$10$ZoyR5TtV3.V.QCMvYVRL2.LFaO13PGl50Bfxxz629zY.fY8BzoIfe',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'name' => 'Pedro Teixeira',
                'email' => '2hraa8b6@anonaddy.me',
                'password' => '$2y$10$5a34SH5APnA3zA5UboCHWOHXeILbsqgiR2ApmTH3j/dOnrF7geR4C',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'name' => 'Mario Rocha',
                'email' => 'marioplusrocha@gmail.com',
                'password' => '$2y$10$5a34SH5APnA3zA5UboCHWOHXeILbsqgiR2ApmTH3j/dOnrF7geR4C',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
        ]);

        // Super Admin Role
        DB::table('user_has_roles')->insert([
            ['role_id' => 1, 'model_type' => 'Backpack\Base\app\Models\BackpackUser', 'user_id' => 1],
            ['role_id' => 1, 'model_type' => 'Backpack\Base\app\Models\BackpackUser', 'user_id' => 2],
            ['role_id' => 1, 'model_type' => 'Backpack\Base\app\Models\BackpackUser', 'user_id' => 3],
        ]);
    }
}
