<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGodfathersHeadquarters extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('godfathers_headquarters', function (Blueprint $table) {
            $table->foreignId('godfather_id')->constrained()->onDelete('cascade');
            $table->foreignId('headquarter_id')->constrained()->onDelete('cascade');

            $table->primary(['godfather_id', 'headquarter_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('godfathers_headquarters');
    }
}
