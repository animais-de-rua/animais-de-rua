<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSocialAccountsTable extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('users', function (Blueprint $table) {
			$table->string('avatar')->after('password')->default("");
		});

		Schema::create('users_social_accounts', function (Blueprint $table) {
			$table->integer('user_id')->unsigned();
			$table->string('provider', 15);
			$table->string('provider_user_id');
			$table->string('token');
			$table->timestamps();

			$table->index(['user_id']);

			$table->foreign('user_id')
				->references('id')
				->on('users')
				->onDelete('cascade');

			$table->primary(['user_id', 'provider']);
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::dropIfExists('users_social_accounts');

		Schema::table('users', function (Blueprint $table) {
			$table->dropColumn('avatar');
		});
	}
}
