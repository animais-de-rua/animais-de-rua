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
            $table->id();
            $table->foreignId('process_id')->nullable()->constrained();
            $table->foreignId('user_id')->nullable()->constrained();
            $table->foreignId('vet_id_1')->nullable()->constrained();
            $table->date('date_1')->nullable();
            $table->foreignId('vet_id_2')->nullable()->constrained();
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
            $table->timestamps();
            $table->softDeletes();
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
