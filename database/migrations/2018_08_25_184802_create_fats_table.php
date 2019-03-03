<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFatsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('fats', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name', 255);
            $table->string('email', 127)->nullable();
            $table->string('phone', 255)->nullable();
            $table->string('territory_id', 6)->nullable();
            $table->integer('user_id')->nullable()->unsigned();

            $table->index(['territory_id']);
            $table->foreign('territory_id')
                ->references('id')
                ->on('territories')
                ->onDelete('set null');

            $table->index(['user_id']);
            $table->foreign('user_id')
                ->references('id')
                ->on('users')
                ->onDelete('set null');

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
        Schema::dropIfExists('fats');
    }
}
