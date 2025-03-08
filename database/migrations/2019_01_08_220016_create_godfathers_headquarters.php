<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGodfathersHeadquarters extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('godfathers_headquarters', function (Blueprint $table) {
            $table->integer('godfather_id')->unsigned();
            $table->integer('headquarter_id')->unsigned();

            $table->index(['godfather_id']);
            $table->foreign('godfather_id')
                ->references('id')
                ->on('godfathers')
                ->onDelete('cascade');

            $table->index(['headquarter_id']);
            $table->foreign('headquarter_id')
                ->references('id')
                ->on('headquarters')
                ->onDelete('cascade');

            $table->primary(['godfather_id', 'headquarter_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::dropIfExists('godfathers_headquarters');
    }
}
