<?php
namespace Database\Seeders;

use App\Enums\PermissionsEnum;
use App\Enums\RolesEnum;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Role::truncate();
        Permission::truncate();
        DB::table('role_has_permissions')->truncate();

        $date = Carbon::now();

        // Roles
        foreach (RolesEnum::values() as $role) {
            Role::firstOrCreate([
                'name' => $role,
            ], [
                'guard_name' => 'backpack',
                'created_at' => $date,
                'updated_at' => $date,
            ]);
        }

        // Permissions
        foreach (PermissionsEnum::values() as $permission) {
            Permission::firstOrCreate([
                'name' => $permission,
            ], [
                'guard_name' => 'backpack',
                'created_at' => $date,
                'updated_at' => $date,
            ]);
        }
    }
}
