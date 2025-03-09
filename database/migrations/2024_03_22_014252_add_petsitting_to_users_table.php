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
            $table->enum('petsitting_role', ['Dog', 'Cat', 'Both'])->nullable()->after('friend_card_expiry');
            $table->string('petsitting_description')->nullable()->after('petsitting_role');
            $table->mediumText('petsitting_image')->nullable()->after('petsitting_description');
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
