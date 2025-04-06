<?php

use App\Enums\Store\VouchersEnum;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVouchersTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('vouchers', function (Blueprint $table) {
            $table->id();
            $table->string('reference', 255);
            $table->string('voucher', 255)->nullable();
            $table->decimal('value', 8, 2)->nullable()->unsigned()->default(null);
            $table->tinyInteger('percent')->unsigned()->nullable();
            $table->string('client_name', 255)->nullable();
            $table->string('client_email', 255)->nullable();
            $table->date('expiration')->nullable();
            $table->enum('status', VouchersEnum::values())->default(VouchersEnum::UNUSED->value);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vouchers');
    }
}
