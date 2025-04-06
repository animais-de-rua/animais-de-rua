<?php

use App\Enums\Store\OrdersEnum;
use App\Enums\Store\PaymentsEnum;
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
            $table->id();
            $table->string('name', 255);
            $table->tinyInteger('vat')->unsigned()->nullable();
            $table->decimal('price', 8, 2)->unsigned()->default(0);
            $table->decimal('price_no_vat', 8, 2)->unsigned()->default(0);
            $table->decimal('expense', 8, 2)->nullable()->unsigned()->default(0);
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('store_orders', function (Blueprint $table) {
            $table->id();
            $table->string('reference', 255);
            $table->text('cart');
            $table->text('recipient');
            $table->text('address');
            $table->foreignId('user_id')->nullable()->constrained();
            $table->date('shipment_date')->nullable();
            $table->decimal('expense', 8, 2)->unsigned()->default(0);
            $table->enum('payment', PaymentsEnum::values())->default(PaymentsEnum::BANK_TRANSFER->value);
            $table->text('receipt')->nullable();
            $table->text('invoice')->nullable();
            $table->text('notes')->nullable();
            $table->enum('status', OrdersEnum::values())->default(OrdersEnum::WAITING);
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('store_orders_products', function (Blueprint $table) {
            $table->foreignId('store_product_id')->nullable()->constrained();
            $table->foreignId('store_order_id')->nullable()->constrained();
            $table->integer('quantity')->default(1)->unsigned();
            $table->decimal('discount', 8, 2)->unsigned()->default(0);
            $table->decimal('discount_no_vat', 8, 2)->unsigned()->default(0);

            $table->unique(['store_product_id', 'store_order_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('store_orders_products');
        Schema::dropIfExists('store_orders');
        Schema::dropIfExists('store_products');
    }
}
