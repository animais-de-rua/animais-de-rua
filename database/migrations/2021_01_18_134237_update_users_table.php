<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateUsersTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->integer('friend_card_number')->after('friend_card_modality_id')->nullable()->unsigned();
            $table->integer('friend_card_expiry')->after('friend_card_number')->nullable()->unsigned();
            $table->text('address')->after('phone')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::table('users', function ($table) {
            $table->dropColumn('friend_card_number');
            $table->dropColumn('friend_card_expiry');
            $table->dropColumn('address');
        });
    }
}
