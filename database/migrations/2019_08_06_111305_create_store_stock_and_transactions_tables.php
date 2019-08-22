<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStoreStockAndTransactionsTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('store_stock', function (Blueprint $table) {
            $table->increments('id');
            $table->text('description')->nullable();
            $table->integer('user_id')->nullable()->unsigned();
            $table->integer('store_product_id')->nullable()->unsigned();
            $table->integer('quantity');
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->index(['user_id']);
            $table->foreign('user_id')
                ->references('id')
                ->on('users')
                ->onDelete('set null');

            $table->index(['store_product_id']);
            $table->foreign('store_product_id')
                ->references('id')
                ->on('store_products')
                ->onDelete('set null');
        });

        Schema::create('store_transactions', function (Blueprint $table) {
            $table->increments('id');
            $table->text('description')->nullable();
            $table->integer('user_id')->nullable()->unsigned();
            $table->decimal('amount', 8, 2)->default(0);
            $table->text('invoice')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->index(['user_id']);
            $table->foreign('user_id')
                ->references('id')
                ->on('users')
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
        Schema::dropIfExists('store_stock');
        Schema::dropIfExists('store_transactions');
    }
}
