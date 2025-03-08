<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterUsersTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('phone', 127)->after('email')->unique()->nullable();
            $table->boolean('status')->after('phone')->default(1);
            $table->text('notes')->after('status')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::table('users', function ($table) {
            $table->dropColumn('phone');
            $table->dropColumn('status');
            $table->dropColumn('notes');
        });
    }
}
