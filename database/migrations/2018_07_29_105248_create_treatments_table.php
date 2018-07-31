<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTreatmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('treatment_types', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name', 127);
            $table->integer('operation_time')->unsigned()->default(0);
            $table->timestamps();
        });

        Schema::create('treatments', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('process_id')->unsigned();
            $table->integer('treatment_type_id')->unsigned();
            $table->integer('vet_id')->unsigned();
            $table->decimal('expense', 8, 2)->unsigned()->default(0);
            $table->date('date');
            $table->timestamps();

            $table->index(['process_id']);
            $table->foreign('process_id')
                ->references('id')
                ->on('processes')
                ->onDelete('cascade');

            $table->index(['treatment_type_id']);
            $table->foreign('treatment_type_id')
                ->references('id')
                ->on('treatment_types')
                ->onDelete('cascade');

            $table->index(['vet_id']);
            $table->foreign('vet_id')
                ->references('id')
                ->on('vets')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('treatments');
        Schema::dropIfExists('treatment_types');
    }
}
