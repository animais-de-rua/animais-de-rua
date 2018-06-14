<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTerritoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('territories', function (Blueprint $table) {
            $table->string('id', 6)->primary();
            $table->string('name', 127);
            $table->enum('level', [1, 2, 3]);
            $table->string('sf', 4)->nullable();
            $table->string('parent_id', 6)->nullable();

            $table->index(['id']);
            $table->index(['parent_id']);

            $table->foreign('parent_id')
                ->references('id')
                ->on('territories')
                ->onDelete('cascade');
        });

        Schema::table('pages', function (Blueprint $table) {
            $table->longText('extras')->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('territories');
    }
}
