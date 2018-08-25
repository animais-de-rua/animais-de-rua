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
            $table->text('name');
            $table->integer('operation_time')->nullable()->unsigned()->default(60);
            $table->timestamps();
        });

        Schema::create('treatments', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('process_id')->unsigned();
            $table->integer('treatment_type_id')->unsigned();
            $table->integer('vet_id')->unsigned();
            $table->integer('user_id')->unsigned()->nullable();
            $table->integer('affected_animals')->unsigned()->default(1);
            $table->decimal('expense', 8, 2)->nullable()->unsigned()->default(0);
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

            $table->index(['user_id']);
            $table->foreign('user_id')
                ->references('id')
                ->on('users')
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
