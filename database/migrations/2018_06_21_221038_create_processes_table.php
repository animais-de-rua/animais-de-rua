<?php

use App\Helpers\EnumHelper;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProcessesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('processes', function (Blueprint $table) {
            $table->increments('id');

            $table->string('name', 255);
            $table->string('contact', 255)->nullable();
            $table->string('phone')->nullable();
            $table->string('email', 127)->nullable();
            $table->string('address', 255)->nullable();
            $table->string('territory_id', 6)->nullable();
            $table->integer('headquarter_id')->unsigned()->nullable();
            $table->integer('user_id')->unsigned()->nullable();
            $table->enum('specie', EnumHelper::values('process.specie'))->default('dog');
            $table->integer('amount_males')->unsigned()->default(0);
            $table->integer('amount_females')->unsigned()->default(0);
            $table->integer('amount_other')->unsigned()->default(0);
            $table->enum('status', EnumHelper::values('process.status'))->default('approving');
            $table->boolean('urgent')->default(0);
            $table->text('history')->nullable();
            $table->text('notes')->nullable();
            $table->text('latlong')->nullable();
            $table->text('images')->nullable();

            $table->index(['headquarter_id']);
            $table->foreign('headquarter_id')
                ->references('id')
                ->on('headquarters')
                ->onDelete('set null');

            $table->index(['territory_id']);
            $table->foreign('territory_id')
                ->references('id')
                ->on('territories')
                ->onDelete('set null');

            $table->index(['user_id']);
            $table->foreign('user_id')
                ->references('id')
                ->on('users')
                ->onDelete('set null');

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
        Schema::dropIfExists('processes');
    }
}
