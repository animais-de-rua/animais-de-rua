<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddTerritoriesToHeadquarters extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('headquarters', function (Blueprint $table) {
            $table->string('territory_id', 6)->nullable();

            $table->index(['territory_id']);

            $table->foreign('territory_id')
                ->references('id')
                ->on('territories')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('headquarters', function (Blueprint $table) {
            $table->dropColumn('territory_id');
        });
    }
}
