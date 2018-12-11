<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePartnersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('partners', function (Blueprint $table) {
            $table->increments('id');
            $table->text('name');
            $table->string('email', 127)->nullable()->unique();
            $table->text('phone1')->nullable();
            $table->text('phone1_info')->nullable();
            $table->text('phone2')->nullable();
            $table->text('phone2_info')->nullable();
            $table->string('url', 255)->nullable();
            $table->string('facebook', 255)->nullable();
            $table->string('instagram', 255)->nullable();
            $table->text('address')->nullable();
            $table->text('address_info')->nullable();
            $table->string('latlong', 255)->nullable();
            $table->text('benefit')->nullable();
            $table->text('notes')->nullable();
            $table->string('image', 255)->nullable();
            $table->boolean('status')->default(1);
            $table->integer('user_id')->unsigned()->nullable();
            $table->timestamps();

            $table->index(['user_id']);
            $table->foreign('user_id')
                ->references('id')
                ->on('users')
                ->onDelete('set null');
        });

        Schema::create('partner_category_list', function (Blueprint $table) {
            $table->increments('id');
            $table->text('name');
            $table->text('description')->nullable();
            $table->timestamps();
        });

        Schema::create('partners_categories', function (Blueprint $table) {
            $table->integer('partner_id')->unsigned();
            $table->integer('partner_category_list_id')->unsigned();

            $table->index(['partner_id']);
            $table->foreign('partner_id')
                ->references('id')
                ->on('partners')
                ->onDelete('set null');

            $table->index(['partner_category_list_id']);
            $table->foreign('partner_category_list_id')
                ->references('id')
                ->on('partner_category_list')
                ->onDelete('set null');

            $table->primary(['partner_id', 'partner_category_list_id']);
        });

        Schema::create('partners_territories', function (Blueprint $table) {
            $table->integer('partner_id')->unsigned();
            $table->string('territory_id', 6);

            $table->index(['partner_id']);
            $table->foreign('partner_id')
                ->references('id')
                ->on('partners')
                ->onDelete('set null');

            $table->index(['territory_id']);
            $table->foreign('territory_id')
                ->references('id')
                ->on('territories')
                ->onDelete('set null');

            $table->primary(['partner_id', 'territory_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('partners_categories');
        Schema::dropIfExists('partners_territories');
        Schema::dropIfExists('partner_category_list');
        Schema::dropIfExists('partners');
    }
}
