<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

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
            $table->string('name', 255);
            $table->string('alias', 255)->nullable();
            $table->string('email', 127)->nullable()->unique();
            $table->string('phone', 255)->nullable();
            $table->string('territory_id', 6)->nullable();
            $table->integer('user_id')->unsigned();

            $table->index(['territory_id']);
            $table->foreign('territory_id')
                ->references('id')
                ->on('territories')
                ->onDelete('cascade');

            $table->index(['user_id']);
            $table->foreign('user_id')
                ->references('id')
                ->on('users')
                ->onDelete('cascade');

            $table->timestamps();
        });

        Schema::create('donations', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('process_id')->nullable()->unsigned();
            $table->integer('godfather_id')->unsigned();
            $table->integer('user_id')->unsigned();

            $table->decimal('value', 8, 2)->nullable()->unsigned()->default(0);
            $table->date('date')->nullable();

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

            $table->index(['user_id']);
            $table->foreign('user_id')
                ->references('id')
                ->on('users')
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
