<?php

use App\Helpers\EnumHelper;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

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
            $table->integer('appointment_id')->unsigned();
            $table->integer('treatment_type_id')->unsigned();
            $table->integer('vet_id')->unsigned();
            $table->integer('user_id')->unsigned()->nullable();
            $table->integer('affected_animals')->unsigned()->default(1);
            $table->integer('affected_animals_new')->unsigned()->default(0);
            $table->decimal('expense', 8, 2)->nullable()->unsigned()->default(0);
            $table->enum('status', EnumHelper::values('treatment.status'))->default('approving');
            $table->date('date');
            $table->timestamps();

            $table->index(['appointment_id']);
            $table->foreign('appointment_id')
                ->references('id')
                ->on('appointments')
                ->onDelete('set null');

            $table->index(['treatment_type_id']);
            $table->foreign('treatment_type_id')
                ->references('id')
                ->on('treatment_types')
                ->onDelete('set null');

            $table->index(['vet_id']);
            $table->foreign('vet_id')
                ->references('id')
                ->on('vets')
                ->onDelete('set null');

            $table->index(['user_id']);
            $table->foreign('user_id')
                ->references('id')
                ->on('users')
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
        Schema::dropIfExists('treatments');
        Schema::dropIfExists('treatment_types');
    }
}
