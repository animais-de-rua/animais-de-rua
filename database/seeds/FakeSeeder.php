<?php

use App\Helpers\EnumHelper;
use App\Models\Adopter;
use App\Models\Adoption;
use App\Models\Appointment;
use App\Models\Donation;
use App\Models\Fat;
use App\Models\Godfather;
use App\Models\Process;
use App\Models\Protocol;
use App\Models\ProtocolRequest;
use App\Models\StoreOrder;
use App\Models\StoreProduct;
use App\Models\StoreStock;
use App\Models\StoreTransaction;
use App\Models\Supplier;
use App\Models\Treatment;
use App\Models\Vet;
use App\Models\Voucher;
use Backpack\Base\app\Models\BackpackUser as User;
use Backpack\PermissionManager\app\Models\Permission;
use Backpack\PermissionManager\app\Models\Role;
use Illuminate\Database\Seeder;

class FakeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Truncate tables
        DB::table('users')->where('id', '>', 1)->delete();
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
        DB::table('store_stock')->truncate();
        DB::table('store_transactions')->truncate();
        DB::table('suppliers')->truncate();
        DB::table('vouchers')->truncate();

        $permissions = EnumHelper::keys('user.permissions');

        // Users
        $this->log('Users');
        factory(User::class, 24)->create()->each(function ($user) use ($permissions) {
            // 66% change to be a volunteer
            if (rand(0, 2)) {
                $user->roles()->save(Role::where('id', 2)->first());

                // Permission
                shuffle($permissions);

                $user->permissions()->save(Permission::where('id', $permissions[0])->first());
                if (rand(0, 1)) {
                    // 50% change to have extra permissions
                    $user->permissions()->save(Permission::where('id', $permissions[1])->first());
                }
            }

            // 33% change to be a FAT
            if (!rand(0, 2)) {
                $user->roles()->save(Role::where('id', 3)->first());
            }
        });

        // Processes
        $this->log('Processes');
        factory(Process::class, 50)->create();

        // Godfathers
        $this->log('Godfathers');
        factory(Godfather::class, 50)->create()->each(function ($godfather) {
            // One donation per godfather
            $godfather->donations()->save(factory(Donation::class)->make());
        });

        // Protocols
        $this->log('Protocols');
        factory(Protocol::class, 6)->create();
        factory(ProtocolRequest::class, 80)->create();

        // Donations
        $this->log('Donations');
        factory(Donation::class, 30)->create();

        // Vets
        $this->log('Vets');
        factory(Vet::class, 50)->create();

        // Appointments
        $this->log('Appointments');
        factory(Appointment::class, 100)->create();

        // Treatments
        $this->log('Treatments');
        factory(Treatment::class, 120)->create();

        // Fats
        $this->log('Fats');
        factory(Fat::class, 50)->create();

        // Adopters
        $this->log('Adopters');
        factory(Adopter::class, 50)->create();

        // Adoptions
        $this->log('Adoptions');
        factory(Adoption::class, 100)->create();

        // Store
        $this->log('Store Products');
        factory(StoreProduct::class, 8)->create();

        $this->log('Store Orders');
        factory(StoreOrder::class, 80)->create()->each(function ($order) {
            $products = StoreProduct::inRandomOrder()->get();
            for ($i = 0; $i < rand(1, 5); $i++) {
                $order->products()->attach([$products[$i]->id => ['quantity' => rand(1, 3)]]);
            }
        });

        // Store Stock
        $this->log('Store Stock');
        factory(StoreStock::class, 50)->create();

        // Store Transactions
        $this->log('Store Transactions');
        factory(StoreTransaction::class, 30)->create();

        // Supliers
        $this->log('Suppliers');
        factory(Supplier::class, 30)->create();

        // Vouchers
        $this->log('Vouchers');
        factory(Voucher::class, 30)->create();
    }

    public function log($entity)
    {
        echo "Seeding: Fake $entity\n";
    }
}
