<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProtocolsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('protocols', function (Blueprint $table) {
            $table->increments('id');
            $table->string('council', 127);
            $table->string('name', 255);
            $table->string('email', 127)->nullable()->unique();
            $table->string('phone', 255)->nullable();
            $table->text('address')->nullable();
            $table->text('description')->nullable();
            $table->string('territory_id', 6)->nullable();
            $table->integer('process_id')->nullable()->unsigned();
            $table->integer('user_id')->unsigned()->nullable();

            $table->index(['territory_id']);
            $table->foreign('territory_id')
                ->references('id')
                ->on('territories')
                ->onDelete('cascade');

            $table->index(['process_id']);
            $table->foreign('process_id')
                ->references('id')
                ->on('processes')
                ->onDelete('cascade');

            $table->index(['user_id']);
            $table->foreign('user_id')
                ->references('id')
                ->on('users')
                ->onDelete('cascade');

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
        Schema::dropIfExists('protocols');
    }
}
