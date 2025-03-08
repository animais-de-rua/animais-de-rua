<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVetsHeadquarters extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('vets_headquarters', function (Blueprint $table) {
            $table->integer('vet_id')->unsigned();
            $table->integer('headquarter_id')->unsigned();

            $table->index(['vet_id']);
            $table->foreign('vet_id')
                ->references('id')
                ->on('vets')
                ->onDelete('cascade');

            $table->index(['headquarter_id']);
            $table->foreign('headquarter_id')
                ->references('id')
                ->on('headquarters')
                ->onDelete('cascade');

            $table->primary(['vet_id', 'headquarter_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::dropIfExists('vets_headquarters');
    }
}
