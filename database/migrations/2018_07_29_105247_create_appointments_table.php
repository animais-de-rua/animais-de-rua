<?php

use App\Enums\Appointment\StatusEnum;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAppointmentsTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
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
            $table->integer('amount_other')->unsigned()->default(0);
            $table->text('notes')->nullable();
            $table->text('notes_deliver')->nullable();
            $table->text('notes_collect')->nullable();
            $table->text('notes_contact')->nullable();
            $table->text('notes_godfather')->nullable();
            $table->text('notes_info')->nullable();
            $table->enum('status', StatusEnum::values())->default(StatusEnum::APPROVING->value);

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
     */
    public function down(): void
    {
        Schema::dropIfExists('appointments');
    }
}
