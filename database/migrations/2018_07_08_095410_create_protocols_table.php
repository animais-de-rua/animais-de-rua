<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProtocolsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('protocols', function (Blueprint $table) {
            $table->id();
            $table->string('name', 255);
            $table->string('email', 127)->nullable()->unique();
            $table->string('phone', 255)->nullable();
            $table->foreignTerritoryId('territory_id');
            $table->foreignId('headquarter_id')->nullable()->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('cascade');
            $table->timestamps();

        });

        Schema::create('protocols_requests', function (Blueprint $table) {
            $table->id();
            $table->string('council', 127);
            $table->string('name', 255);
            $table->string('email', 127)->nullable()->unique();
            $table->string('phone', 255)->nullable();
            $table->text('address')->nullable();
            $table->text('description')->nullable();
            $table->foreignTerritoryId('territory_id');
            $table->foreignId('process_id')->nullable()->constrained()->onDelete('cascade');
            $table->foreignId('protocol_id')->nullable()->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('cascade');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('protocols_requests');
        Schema::dropIfExists('protocols');
    }
}
