<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Factory as EloquentFactory;

use App\User;
use App\Models\Process;
use App\Models\Godfather;
use App\Models\Donation;
use App\Models\Vet;

class FakeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Users
        factory(User::class, 24)->create();

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
    }
}
