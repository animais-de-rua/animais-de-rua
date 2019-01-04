<?php

use App\Helpers\EnumHelper;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVetsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vets', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name', 255);
            $table->string('email', 127)->nullable()->unique();
            $table->string('phone', 255)->nullable();
            $table->string('url', 255)->nullable();
            $table->string('address', 255)->nullable();
            $table->string('latlong', 255)->nullable();
            $table->enum('status', EnumHelper::values('vet.status'))->default('active');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('vets');
    }
}
