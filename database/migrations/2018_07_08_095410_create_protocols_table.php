<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProtocolsTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('protocols', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name', 255);
            $table->string('email', 127)->nullable()->unique();
            $table->string('phone', 255)->nullable();
            $table->string('territory_id', 6)->nullable();
            $table->integer('headquarter_id')->unsigned()->nullable();
            $table->integer('user_id')->unsigned()->nullable();

            $table->index(['territory_id']);
            $table->foreign('territory_id')
                ->references('id')
                ->on('territories')
                ->onDelete('set null');

            $table->index(['headquarter_id']);
            $table->foreign('headquarter_id')
                ->references('id')
                ->on('headquarters')
                ->onDelete('set null');

            $table->index(['user_id']);
            $table->foreign('user_id')
                ->references('id')
                ->on('users')
                ->onDelete('set null');

            $table->timestamps();
        });

        Schema::create('protocols_requests', function (Blueprint $table) {
            $table->increments('id');
            $table->string('council', 127);
            $table->string('name', 255);
            $table->string('email', 127)->nullable()->unique();
            $table->string('phone', 255)->nullable();
            $table->text('address')->nullable();
            $table->text('description')->nullable();
            $table->string('territory_id', 6)->nullable();
            $table->integer('process_id')->nullable()->unsigned();
            $table->integer('protocol_id')->nullable()->unsigned();
            $table->integer('user_id')->unsigned()->nullable();

            $table->index(['territory_id']);
            $table->foreign('territory_id')
                ->references('id')
                ->on('territories')
                ->onDelete('set null');

            $table->index(['process_id']);
            $table->foreign('process_id')
                ->references('id')
                ->on('processes')
                ->onDelete('set null');

            $table->index(['protocol_id']);
            $table->foreign('protocol_id')
                ->references('id')
                ->on('protocols')
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
     */
    public function down()
    {
        Schema::dropIfExists('protocols_requests');
        Schema::dropIfExists('protocols');
    }
}
