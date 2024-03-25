<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPetsittingToUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('petsitting_role')->after('friend_card_expiry');
            $table->string('petsitting_description')->after('petsitting_role')->nullable();
            $table->string('petsitting_image')->after('petsitting_description');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('petsitting_role');
            $table->dropColumn('petsitting_description');
            $table->dropColumn('petsitting_image');
        });
    }
}
