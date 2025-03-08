<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAdoptersTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('adopters', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('email', 127)->nullable();
            $table->string('phone', 255)->nullable();
            $table->string('address', 255);
            $table->string('zip_code', 255);
            $table->string('id_card', 255);
            $table->string('territory_id', 6)->nullable();
            $table->integer('user_id')->unsigned()->nullable();
            $table->timestamps();

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
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::dropIfExists('adopters');
    }
}
