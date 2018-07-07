<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateHeadquarterTerritories extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('headquarters', function (Blueprint $table) {
            $table->dropForeign('headquarters_territory_id_foreign');
            $table->dropColumn('territory_id');
        });

        Schema::create('headquarters_territories', function (Blueprint $table) {

            $table->integer('headquarter_id')->unsigned();
            $table->string('territory_id', 6);

            $table->index(['headquarter_id']);
            $table->foreign('headquarter_id')
                ->references('id')
                ->on('headquarters')
                ->onDelete('cascade');

            $table->index(['territory_id']);
            $table->foreign('territory_id')
                ->references('id')
                ->on('territories')
                ->onDelete('cascade');

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

        Schema::table('headquarters', function (Blueprint $table) {
            $table->string('territory_id', 6)->nullable();

            $table->index(['territory_id']);

            $table->foreign('territory_id')
                ->references('id')
                ->on('territories')
                ->onDelete('cascade');
        });
    }
}
