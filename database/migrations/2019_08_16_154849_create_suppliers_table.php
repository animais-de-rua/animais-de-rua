<?php

use App\Helpers\EnumHelper;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSuppliersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('suppliers', function (Blueprint $table) {
            $table->increments('id');
            $table->string('reference', 255);
            $table->integer('store_order_id')->nullable()->unsigned();
            $table->integer('store_product_id')->nullable()->unsigned();
            $table->text('notes')->nullable();
            $table->enum('status', EnumHelper::values('store.supplier'))->default('waiting_payment');
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
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('supplier');
    }
}
