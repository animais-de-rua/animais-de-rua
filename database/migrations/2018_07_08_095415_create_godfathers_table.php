<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGodfathersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('godfathers', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name', 127);
            $table->string('alias', 127)->nullable();
            $table->string('email', 127)->nullable()->unique();
            $table->string('phone', 31)->nullable();
            $table->string('territory_id', 6)->nullable();

            $table->index(['territory_id']);
            $table->foreign('territory_id')
                ->references('id')
                ->on('territories')
                ->onDelete('cascade');

            $table->timestamps();
        });

        Schema::create('donations', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('process_id')->unsigned();
            $table->integer('godfather_id')->unsigned();

            $table->decimal('value', 8, 2)->unsigned()->default(0);
            $table->date('date');

            $table->index(['process_id']);
            $table->foreign('process_id')
                ->references('id')
                ->on('processes')
                ->onDelete('cascade');

            $table->index(['godfather_id']);
            $table->foreign('godfather_id')
                ->references('id')
                ->on('godfathers')
                ->onDelete('cascade');

            // $table->unique(['process_id', 'godfather_id']);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('donations');
        Schema::dropIfExists('godfathers');
    }
}
