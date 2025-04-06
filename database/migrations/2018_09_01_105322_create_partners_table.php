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
            $table->id();
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
            $table->text('promo_code')->nullable();
            $table->boolean('status')->default(1);
            $table->foreignId('user_id')->nullable()->constrained();
            $table->timestamps();
        });

        Schema::create('partner_category_list', function (Blueprint $table) {
            $table->id();
            $table->text('name');
            $table->text('description')->nullable();
            $table->timestamps();
        });

        Schema::create('partners_categories', function (Blueprint $table) {
            $table->foreignId('partner_id')->constrained();
            $table->foreignId('partner_category_list_id')->constrained('partner_category_list');

            $table->primary(['partner_id', 'partner_category_list_id']);
        });

        Schema::create('partners_territories', function (Blueprint $table) {
            $table->foreignId('partner_id')->constrained();
            $table->foreignTerritoryId('territory_id');

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
