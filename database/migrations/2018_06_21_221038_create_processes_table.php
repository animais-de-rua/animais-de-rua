<?php

use App\Enums\Animal\SpeciesEnum;
use App\Enums\Process\StatusEnum;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProcessesTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('processes', function (Blueprint $table) {
            $table->id();
            $table->string('name', 255);
            $table->string('contact', 255)->nullable();
            $table->string('phone')->nullable();
            $table->string('email', 127)->nullable();
            $table->string('address', 255)->nullable();
            $table->foreignTerritoryId('territory_id')->nullable();
            $table->foreignId('headquarter_id')->nullable()->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('cascade');
            $table->enum('specie', SpeciesEnum::values())->default(SpeciesEnum::DOG->value);
            $table->integer('amount_males')->unsigned()->default(0);
            $table->integer('amount_females')->unsigned()->default(0);
            $table->integer('amount_other')->unsigned()->default(0);
            $table->enum('status', StatusEnum::values())->default(StatusEnum::APPROVING->value);
            $table->boolean('urgent')->default(0);
            $table->text('history')->nullable();
            $table->text('notes')->nullable();
            $table->text('latlong')->nullable();
            $table->text('images')->nullable();
            $table->boolean('contacted')->default(0);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('processes');
    }
}
