<?php

use App\Helpers\EnumHelper;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAppointmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('appointments', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('process_id')->nullable()->unsigned();
            $table->integer('user_id')->nullable()->unsigned();
            $table->integer('vet_id_1')->nullable()->unsigned();
            $table->date('date_1')->nullable();
            $table->integer('vet_id_2')->nullable()->unsigned();
            $table->date('date_2')->nullable();
            $table->integer('amount_males')->unsigned()->default(0);
            $table->integer('amount_females')->unsigned()->default(0);
            $table->text('notes')->nullable();
            $table->enum('status', EnumHelper::values('appointment.status'))->default('approving');

            $table->index(['user_id']);
            $table->foreign('user_id')
                ->references('id')
                ->on('users')
                ->onDelete('set null');

            $table->index(['process_id']);
            $table->foreign('process_id')
                ->references('id')
                ->on('processes')
                ->onDelete('set null');

            $table->index(['vet_id_1']);
            $table->foreign('vet_id_1')
                ->references('id')
                ->on('vets')
                ->onDelete('set null');

            $table->index(['vet_id_2']);
            $table->foreign('vet_id_2')
                ->references('id')
                ->on('vets')
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
        Schema::dropIfExists('appointments');
    }
}
