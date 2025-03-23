<?php

use App\Enums\Veterinary\StatusEnum;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVetsTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('vets', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name', 255);
            $table->string('email', 127)->nullable()->unique();
            $table->string('phone', 255)->nullable();
            $table->string('url', 255)->nullable();
            $table->string('address', 255)->nullable();
            $table->string('latlong', 255)->nullable();
            $table->enum('status', StatusEnum::values())->default(StatusEnum::ACTIVE->value);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vets');
    }
}
