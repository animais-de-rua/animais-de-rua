<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Factory as EloquentFactory;

use Backpack\Base\app\Models\BackpackUser as User;
use App\Models\Process;
use App\Models\Godfather;
use App\Models\Donation;
use App\Models\Vet;
use App\Models\Treatment;
use Backpack\PermissionManager\app\Models\Role;
use Backpack\PermissionManager\app\Models\Permission;
use App\Helpers\EnumHelper;

class FakeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $permissions = EnumHelper::keys('user.permissions');

        // Users
        factory(User::class, 24)->create()->each(function($user) use ($permissions) {
            // 66% change to be a volunteer
            if(rand(0, 2)) {
                $user->roles()->save(Role::where('id', 2)->first());

                // Permission
                shuffle($permissions);

                $user->permissions()->save(Permission::where('id', $permissions[0])->first());
                if(rand(0, 1)) { // 50% change to have extra permissions
                    $user->permissions()->save(Permission::where('id', $permissions[1])->first());
                }
            }

            // 33% change to be a FAT
            if(!rand(0, 2)) {
                $user->roles()->save(Role::where('id', 3)->first());
            }
        });

        // Processes
        factory(Process::class, 50)->create();

        // Godfathers
        factory(Godfather::class, 50)->create()->each(function($godfather) {
            // One donation per godfather
            $godfather->donations()->save(factory(Donation::class)->make());
        });

        // Donations
        factory(Donation::class, 30)->create();

        // Vets
        factory(Vet::class, 50)->create();

        // Treatments
        factory(Treatment::class, 120)->create();
    }
}
