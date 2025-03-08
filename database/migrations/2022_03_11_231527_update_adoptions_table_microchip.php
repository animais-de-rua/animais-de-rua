<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateAdoptionsTableMicrochip extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('adoptions', function (Blueprint $table) {
            $table->string('microchip')->after('gender')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::table('adoptions', function (Blueprint $table) {
            $table->dropColumn('microchip');
        });
    }
}
