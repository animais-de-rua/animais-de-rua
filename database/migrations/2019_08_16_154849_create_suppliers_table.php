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
            $table->id();
            $table->string('reference', 255);
            $table->foreignId('store_order_id')->nullable()->constrained()->onDelete('cascade');
            $table->foreignId('store_product_id')->nullable()->constrained()->onDelete('cascade');
            $table->text('invoice')->nullable();
            $table->text('notes')->nullable();
            $table->enum('status', SuppliersEnum::values())->default(SuppliersEnum::WAITING_PAYMENT->value);
            $table->timestamps();
            $table->softDeletes();
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
