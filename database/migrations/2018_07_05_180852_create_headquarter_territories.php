<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHeadquarterTerritories extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('headquarters_territories', function (Blueprint $table) {

            $table->integer('headquarter_id')->unsigned();
            $table->string('territory_id', 6);

            $table->index(['headquarter_id']);
            $table->foreign('headquarter_id')
                ->references('id')
                ->on('headquarters')
                ->onDelete('set null');

            $table->index(['territory_id']);
            $table->foreign('territory_id')
                ->references('id')
                ->on('territories')
                ->onDelete('set null');

            $table->primary(['headquarter_id', 'territory_id']);
            $table->unique(['territory_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('headquarters_territories');
    }
}
