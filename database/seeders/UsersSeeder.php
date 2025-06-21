<?php

namespace Database\Seeders;

use App\Models\User;
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
        DB::table(config('permission.table_names.model_has_roles'))->truncate();
        DB::table(config('permission.table_names.model_has_permissions'))->truncate();
        User::truncate();

        $i = -1;
        while ($this->command->confirm('Add '.($i++ ? 'an' : 'another').' admin user?')) {
            $name = $this->command->ask('Name');
            $mail = $this->command->ask("{$name}'s email");
            $pass = $this->command->secret("{$name}'s password");

            $user = User::insert([
                'name' => $name,
                'email' => $mail,
                'password' => bcrypt($pass),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);

            // Admin Role
            DB::table('user_has_roles')->insert([
                'role_id' => 1,
                'model_type' => User::class,
                config('permission.column_names.model_morph_key') => DB::getPdo()->lastInsertId(),
            ]);
        }
    }
}
