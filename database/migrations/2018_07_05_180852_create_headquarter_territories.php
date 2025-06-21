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
            $table->foreignId('headquarter_id')->constrained()->onDelete('cascade');
            $table->foreignTerritoryId('territory_id');

            $table->primary(['headquarter_id', 'territory_id']);
        });

        Schema::create('headquarters_territories_range', function (Blueprint $table) {
            $table->foreignId('headquarter_id')->constrained()->onDelete('cascade');
            $table->foreignTerritoryId('territory_id');

            $table->primary(['headquarter_id', 'territory_id'], 'headquarters_territories_range_primary');
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
        Schema::dropIfExists('headquarters_territories_range');
    }
}
