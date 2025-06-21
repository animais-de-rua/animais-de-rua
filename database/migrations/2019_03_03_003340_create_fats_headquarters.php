<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFatsHeadquarters extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('fats_headquarters', function (Blueprint $table) {
            $table->foreignId('fat_id')->constrained()->onDelete('cascade');
            $table->foreignId('headquarter_id')->constrained()->onDelete('cascade');

            $table->primary(['fat_id', 'headquarter_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('fats_headquarters');
    }
}
