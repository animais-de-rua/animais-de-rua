<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAdoptersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
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
            $table->date('adoption_date');
            $table->string('id_card', 255);
            $table->string('territory_id', 6)->nullable();
            $table->integer('adoption_id')->unsigned()->nullable();
            $table->integer('user_id')->unsigned()->nullable();
            $table->timestamps();

            $table->index(['territory_id']);
            $table->foreign('territory_id')
                ->references('id')
                ->on('territories')
                ->onDelete('set null');

            $table->index(['adoption_id']);
            $table->foreign('adoption_id')
                ->references('id')
                ->on('adoptions')
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
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('adopters');
    }
}
