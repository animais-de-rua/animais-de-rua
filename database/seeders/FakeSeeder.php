<?php

namespace Database\Seeders;

use App\Enums\PermissionsEnum;
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
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class FakeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Truncate tables
        DB::table('users')->where('id', '>', 2)->delete();
        DB::table('processes')->truncate();
        DB::table('godfathers')->truncate();
        DB::table('donations')->truncate();
        DB::table('vets')->truncate();
        DB::table('treatments')->truncate();
        DB::table('appointments')->truncate();
        DB::table('fats')->truncate();
        DB::table('adopters')->truncate();
        DB::table('adoptions')->truncate();
        DB::table('protocols_requests')->truncate();
        DB::table('protocols')->truncate();
        DB::table('store_products')->truncate();
        DB::table('store_orders')->truncate();
        DB::table('store_orders_products')->truncate();
        DB::table('store_petsitting_requests')->truncate();
        DB::table('store_stock')->truncate();
        DB::table('store_transactions')->truncate();
        DB::table('suppliers')->truncate();
        DB::table('vouchers')->truncate();

        $permissions = PermissionsEnum::values();
        $headquarters_count = Headquarter::count();

        // Users
        $this->log('Users');
        User::factory(24)->create()->each(function ($user) use ($permissions, $headquarters_count) {
            // 66% change to be a volunteer
            if (rand(0, 2)) {
                $user->roles()->save(Role::where('id', 2)->first());

                // Permission
                shuffle($permissions);

                $user->permissions()->save(Permission::where('id', 1)->first());
                if (rand(0, 1)) {
                    // 50% change to have extra permissions
                    $user->permissions()->save(Permission::where('id', 2)->first());
                }
            }

            // 33% change to be a FAT
            if (!rand(0, 2)) {
                $user->roles()->save(Role::where('id', 3)->first());
            }

            // Randomly assign headquarters
            $randomCount = rand(1, $headquarters_count);
            $headquarters = Headquarter::inRandomOrder()->take($randomCount)->get();
            $user->headquarters()->syncWithoutDetaching($headquarters);
        });

        // Processes
        $this->log('Processes');
        Process::factory(50)->create();

        // Godfathers
        $this->log('Godfathers');
        Godfather::factory(50)->create()->each(function ($godfather) {
            // One donation per godfather
            $godfather->donations()->save(Donation::factory()->make());
        });

        // Protocols
        $this->log('Protocols');
        Protocol::factory(6)->create();
        ProtocolRequest::factory(80)->create();

        // Donations
        $this->log('Donations');
        Donation::factory(30)->create();

        // Vets
        $this->log('Vets');
        Vet::factory(50)->create();

        // Appointments
        $this->log('Appointments');
        Appointment::factory(100)->create();

        // Treatments
        $this->log('Treatments');
        Treatment::factory(120)->create();

        // Fats
        $this->log('Fats');
        Fat::factory(50)->create();

        // Adopters
        $this->log('Adopters');
        Adopter::factory(50)->create();

        // Adoptions
        $this->log('Adoptions');
        Adoption::factory(100)->create();

        // Store
        $this->log('Store Products');
        StoreProduct::factory(8)->create();

        $this->log('Store Orders');
        StoreOrder::factory(80)->create()->each(function ($order) {
            $products = StoreProduct::inRandomOrder()->get();
            for ($i = 0; $i < rand(1, 5); ++$i) {
                $order->products()->attach([$products[$i]->id => ['quantity' => rand(1, 3)]]);
            }
        });

        // Store Stock
        $this->log('Store Stock');
        StoreStock::factory(50)->create();

        // Store Transactions
        $this->log('Store Transactions');
        StoreTransaction::factory(30)->create();

        // Supliers
        $this->log('Suppliers');
        Supplier::factory(30)->create();

        // Vouchers
        $this->log('Vouchers');
        Voucher::factory(30)->create();
    }

    public function log($entity): void
    {
        echo "Seeding: Fake {$entity}\n";
    }
}
