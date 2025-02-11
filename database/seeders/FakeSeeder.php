<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class FakeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Delete not admin users
        User::doesnthave('roles')->delete();

        // Set Auto increment
        $total = User::count();
        DB::statement('ALTER TABLE users AUTO_INCREMENT = '.($total + 1));

        // Users
        $this->log('Users');
        User::factory()->count(30 - $total)->create();
    }

    /**
     * Log to console
     */
    public function log(string $entity): void
    {
        echo "Seeding: Fake $entity\n";
    }
}
