<?php

use App\Helpers\EnumHelper;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVouchersTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('vouchers', function (Blueprint $table) {
            $table->increments('id');
            $table->string('reference', 255);
            $table->string('voucher', 255)->nullable();
            $table->decimal('value', 8, 2)->nullable()->unsigned()->default(0);
            $table->string('client_name', 255)->nullable();
            $table->string('client_email', 255)->nullable();
            $table->date('expiration')->nullable();
            $table->enum('status', EnumHelper::values('store.voucher'))->default('unused');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::dropIfExists('vouchers');
    }
}
