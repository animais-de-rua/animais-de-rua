<?php

use Illuminate\Database\Seeder;
use Carbon\Carbon;

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
        $roles = [
            'admin', 
            'volunteer'];

        foreach ($roles as $role) {
            DB::table('roles')->insert(['created_at' => $date, 'updated_at' => $date, 'name' => $role]);
        }

        // Permissions
        $permissions = [
            'processes',
            'counters',
            'adoptions',
            'accountancy',
            'website',
            'store',
            'friend card',
            'vets',
            'protocols',
            'council'];

        foreach ($permissions as $i => $permission) {
            DB::table('permissions')->insert(['created_at' => $date, 'updated_at' => $date, 'name' => $permission]);
            DB::table('role_has_permissions')->insert(['role_id' => 1, 'permission_id' => $i+1]);
        }
    }
}
