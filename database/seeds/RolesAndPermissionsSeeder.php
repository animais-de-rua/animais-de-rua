<?php

use App\Helpers\EnumHelper;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('roles')->truncate();
        DB::table('permissions')->truncate();
        DB::table('role_has_permissions')->truncate();

        $date = Carbon::now();

        // Roles
        foreach (EnumHelper::values('user.roles') as $role) {
            DB::table('roles')->insert(['created_at' => $date, 'updated_at' => $date, 'name' => $role]);
        }

        // Permissions
        foreach (EnumHelper::values('user.permissions') as $i => $permission) {
            DB::table('permissions')->insert(['created_at' => $date, 'updated_at' => $date, 'name' => $permission, 'guard_name' => backpack_guard_name()]);
            DB::table('role_has_permissions')->insert(['role_id' => 1, 'permission_id' => $i + 1]);
        }
    }
}
