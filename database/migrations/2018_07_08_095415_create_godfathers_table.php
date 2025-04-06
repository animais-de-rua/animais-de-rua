<?php

use App\Enums\Donation\TypesEnum;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGodfathersTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('godfathers', function (Blueprint $table) {
            $table->id();
            $table->string('name', 255);
            $table->string('alias', 255)->nullable();
            $table->string('email', 127)->nullable();
            $table->string('phone', 255)->nullable();
            $table->text('notes')->nullable();
            $table->foreignTerritoryId('territory_id')->nullable();
            $table->foreignId('user_id')->nullable()->constrained();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('donations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('process_id')->nullable()->constrained();
            $table->foreignId('user_id')->nullable()->constrained();
            $table->foreignId('godfather_id')->nullable()->constrained();
            $table->foreignId('headquarter_id')->nullable()->constrained();
            $table->foreignId('protocol_id')->nullable()->constrained();
            $table->enum('type', TypesEnum::values());
            $table->decimal('value', 8, 2)->nullable()->unsigned()->default(0);
            $table->date('date')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('donations');
        Schema::dropIfExists('godfathers');
    }
}
