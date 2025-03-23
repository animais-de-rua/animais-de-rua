<?php

use App\Enums\Store\SuppliersEnum;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSuppliersTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('suppliers', function (Blueprint $table) {
            $table->increments('id');
            $table->string('reference', 255);
            $table->integer('store_order_id')->nullable()->unsigned();
            $table->integer('store_product_id')->nullable()->unsigned();
            $table->text('invoice')->nullable();
            $table->text('notes')->nullable();
            $table->enum('status', SuppliersEnum::values())->default(SuppliersEnum::WAITING_PAYMENT->value);
            $table->timestamps();

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
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('suppliers');
    }
}
