<?php

use App\Helpers\EnumHelper;
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
            $table->increments('id');
            $table->text('name');
            $table->text('description');
            $table->string('paypal_code', 255);
            $table->integer('amount')->unsigned()->default(0);
            $table->enum('type', EnumHelper::values('general.friend_card_modalities'));
            $table->timestamps();
        });

        Schema::table('users', function (Blueprint $table) {
            $table->integer('friend_card_modality_id')->after('headquarter_id')->unsigned()->nullable();

            $table->index(['friend_card_modality_id']);
            $table->foreign('friend_card_modality_id')
                ->references('id')
                ->on('friend_card_modalities')
                ->onDelete('set null');
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
            $table->dropForeign('users_friend_card_modality_id_foreign');
            $table->dropIndex('users_friend_card_modality_id_index');

            $table->dropColumn('friend_card_modality_id');
        });

        Schema::dropIfExists('friend_card_modalities');
    }
}
