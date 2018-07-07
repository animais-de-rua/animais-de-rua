<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateHeadquartersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('headquarters', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name', 31);
            $table->string('address', 127)->nullable();
            $table->string('phone', 31)->nullable();
            $table->string('mail', 127)->nullable();
            $table->text('description')->nullable();
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
        Schema::dropIfExists('headquarters');
    }
}
