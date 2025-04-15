<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVetsHeadquarters extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vets_headquarters', function (Blueprint $table) {
            $table->foreignId('vet_id')->constrained()->onDelete('cascade');
            $table->foreignId('headquarter_id')->constrained()->onDelete('cascade');

            $table->primary(['vet_id', 'headquarter_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('vets_headquarters');
    }
}
