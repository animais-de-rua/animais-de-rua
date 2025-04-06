<?php

use App\Enums\General\FriendCardModalitiesEnum;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFriendCardTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('friend_card_modalities', function (Blueprint $table) {
            $table->id();
            $table->text('name');
            $table->text('description');
            $table->string('paypal_code', 255);
            $table->integer('amount')->unsigned()->default(0);
            $table->enum('type', FriendCardModalitiesEnum::values());
            $table->tinyInteger('visible')->default(1);
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::table('users', function (Blueprint $table) {
            $table->foreignId('friend_card_modality_id')->after('email')->nullable()->constrained();
            $table->integer('friend_card_number')->nullable()->unsigned();
            $table->integer('friend_card_expiry')->nullable()->unsigned();
            $table->text('address')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function ($table) {
            $table->dropForeign(['friend_card_modality_id']);
            $table->dropColumn('friend_card_modality_id');
            $table->dropColumn('friend_card_number');
            $table->dropColumn('friend_card_expiry');
            $table->dropColumn('address');
        });

        Schema::dropIfExists('friend_card_modalities');
    }
}
