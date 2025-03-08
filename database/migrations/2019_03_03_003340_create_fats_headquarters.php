<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFatsHeadquarters extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('fats_headquarters', function (Blueprint $table) {
            $table->integer('fat_id')->unsigned();
            $table->integer('headquarter_id')->unsigned();

            $table->index(['fat_id']);
            $table->foreign('fat_id')
                ->references('id')
                ->on('fats')
                ->onDelete('cascade');

            $table->index(['headquarter_id']);
            $table->foreign('headquarter_id')
                ->references('id')
                ->on('headquarters')
                ->onDelete('cascade');

            $table->primary(['fat_id', 'headquarter_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::dropIfExists('fats_headquarters');
    }
}
