<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStoreManagementTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('store_products', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name', 255);
            $table->decimal('price', 8, 2)->unsigned()->default(0);
            $table->decimal('expense', 8, 2)->nullable()->unsigned()->default(0);
            $table->timestamps();
        });

        Schema::create('store_orders', function (Blueprint $table) {
            $table->increments('id');
            $table->string('reference', 255);
            $table->text('cart');
            $table->text('recipient');
            $table->text('address');
            $table->integer('user_id')->nullable()->unsigned();
            $table->date('shipment_date')->nullable();
            $table->decimal('expense', 8, 2)->unsigned()->default(0);
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->index(['user_id']);
            $table->foreign('user_id')
                ->references('id')
                ->on('users')
                ->onDelete('set null');
        });

        Schema::create('store_orders_products', function (Blueprint $table) {
            $table->integer('store_product_id')->nullable()->unsigned();
            $table->integer('store_order_id')->nullable()->unsigned();
            $table->integer('quantity')->default(1)->unsigned();

            $table->index(['store_product_id']);
            $table->foreign('store_product_id')
                ->references('id')
                ->on('store_products')
                ->onDelete('set null');

            $table->index(['store_order_id']);
            $table->foreign('store_order_id')
                ->references('id')
                ->on('store_orders')
                ->onDelete('set null');

            $table->unique(['store_product_id', 'store_order_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('store_orders_products');
        Schema::dropIfExists('store_orders');
        Schema::dropIfExists('store_products');
    }
}
