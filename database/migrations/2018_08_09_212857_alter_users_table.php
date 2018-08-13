<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('phone', 31)->after('email')->unique()->nullable();
            $table->integer('headquarter_id')->after('phone')->unsigned()->nullable();
            $table->boolean('status')->after('headquarter_id')->default(1);

            $table->index(['headquarter_id']);
            $table->foreign('headquarter_id')
                ->references('id')
                ->on('headquarters')
                ->onDelete('cascade');
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
            $table->dropForeign('users_headquarter_id_foreign');
            $table->dropIndex('users_headquarter_id_index');

            $table->dropColumn('phone');
            $table->dropColumn('headquarter_id');
            $table->dropColumn('status');
        });
    }
}
