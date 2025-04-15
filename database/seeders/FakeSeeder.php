<?php

namespace Database\Seeders;

use App\Enums\PermissionsEnum;
use App\Enums\RolesEnum;
use App\Models\Adopter;
use App\Models\Adoption;
use App\Models\Appointment;
use App\Models\Donation;
use App\Models\Fat;
use App\Models\Godfather;
use App\Models\Headquarter;
use App\Models\Process;
use App\Models\Protocol;
use App\Models\ProtocolRequest;
use App\Models\StoreOrder;
use App\Models\StorePetsittingRequests;
use App\Models\StoreProduct;
use App\Models\StoreStock;
use App\Models\StoreTransaction;
use App\Models\Supplier;
use App\Models\Treatment;
use App\Models\User;
use App\Models\Vet;
use App\Models\Voucher;
use Backpack\PermissionManager\app\Models\Permission;
use Backpack\PermissionManager\app\Models\Role;
use Exception;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;

class FakeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Truncate tables
        Schema::disableForeignKeyConstraints();
        Process::truncate();
        Godfather::truncate();
        Donation::truncate();
        Vet::truncate();
        Treatment::truncate();
        Appointment::truncate();
        Fat::truncate();
        Adopter::truncate();
        Adoption::truncate();
        ProtocolRequest::truncate();
        Protocol::truncate();
        StoreProduct::truncate();
        StoreOrder::truncate();
        StorePetsittingRequests::truncate();
        StoreStock::truncate();
        Supplier::truncate();
        Voucher::truncate();
        User::whereHas('roles', fn (Builder $query): Builder => $query->whereNot('name', RolesEnum::ADMIN->value))->delete();
        Schema::enableForeignKeyConstraints();

        $permissions = PermissionsEnum::values();
        $headquarters_count = Headquarter::count();

        // Users
        $this->log('Users');
        User::factory()->count(24)->create()->each(function (User $user) use ($permissions, $headquarters_count): void {
            // 66% change to be a volunteer
            if (rand(0, 2)) {
                $role = Role::find(RolesEnum::VOLUNTEER);
                if ($role) {
                    $user->roles()->save($role);
                }

                // Permission
                shuffle($permissions);

                $permission1 = Permission::find(PermissionsEnum::PROCESSES);
                if ($permission1) {
                    $user->permissions()->save($permission1);
                }if (rand(0, 1)) {
                    // 50% change to have extra permissions
                    $permission2 = Permission::find(PermissionsEnum::APPOINTMENTS);
                    if ($permission2) {
                        $user->permissions()->save($permission2);
                    }
                }
            }

            // 33% change to be a FAT
            if (! rand(0, 2)) {
                $role = Role::find(RolesEnum::STORE);
                if ($role) {
                    $user->roles()->save($role);
                }
            }

            // Randomly assign headquarters
            $randomCount = rand(1, $headquarters_count);
            $headquarters = Headquarter::inRandomOrder()->take($randomCount)->get();
            $user->headquarters()->syncWithoutDetaching($headquarters);
        });

        // Processes
        $this->log('Processes');
        Process::factory()->count(50)->create();

        // Godfathers
        $this->log('Godfathers');
        Godfather::factory()->count(50)->create()->each(function (Godfather $godfather):void {
            // One donation per godfather
            $godfather->donations()->save(Donation::factory()->make());
        });

        // Protocols
        $this->log('Protocols');
        Protocol::factory()->count(6)->create();
        ProtocolRequest::factory()->count(80)->create();

        // Donations
        $this->log('Donations');
        Donation::factory()->count(30)->create();

        // Vets
        $this->log('Vets');
        Vet::factory()->count(50)->create();

        // Appointments
        $this->log('Appointments');
        Appointment::factory()->count(100)->create();

        // Treatments
        $this->log('Treatments');
        Treatment::factory()->count(120)->create();

        // Fats
        $this->log('Fats');
        Fat::factory()->count(50)->create();

        // Adopters
        $this->log('Adopters');
        Adopter::factory()->count(50)->create();

        // Adoptions
        $this->log('Adoptions');
        Adoption::factory()->count(100)->create();

        // Store
        $this->log('Store Products');
        StoreProduct::factory()->count(8)->create();

        $this->log('Store Orders');
        StoreOrder::factory()->count(80)->create()->each(function (StoreOrder $order): void {
            $products = StoreProduct::inRandomOrder()->get();
            for ($i = 0; $i < rand(1, 5); $i++) {
                try {
                    $order->products()->attach([$products[$i]?->id => ['quantity' => rand(1, 3)]]);
                } catch (Exception $e) { }
            }
        });

        // Store Stock
        $this->log('Store Stock');
        StoreStock::factory()->count(50)->create();

        // Store Transactions
        $this->log('Store Transactions');
        StoreTransaction::factory()->count(30)->create();

        // Suppliers
        $this->log('Suppliers');
        Supplier::factory()->count(30)->create();

        // Vouchers
        $this->log('Vouchers');
        Voucher::factory()->count(30)->create();
    }

    public function log(string $entity): void
    {
        $this->command->line("  Seeding Fake $entity");
    }
}
