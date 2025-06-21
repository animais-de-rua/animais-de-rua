<?php

use App\Enums\Treatment\StatusEnum;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTreatmentsTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('treatment_types', function (Blueprint $table) {
            $table->id();
            $table->text('name');
            $table->integer('operation_time')->nullable()->unsigned()->default(60);
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('treatments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('appointment_id')->nullable()->constrained()->onDelete('cascade');
            $table->foreignId('treatment_type_id')->nullable()->constrained()->onDelete('cascade');
            $table->foreignId('vet_id')->nullable()->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('cascade');
            $table->integer('affected_animals')->unsigned()->default(1);
            $table->integer('affected_animals_new')->unsigned()->default(0);
            $table->decimal('expense', 8, 2)->nullable()->unsigned()->default(0);
            $table->enum('status', StatusEnum::values())->default(StatusEnum::APPROVING);
            $table->date('date');
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('treatments');
        Schema::dropIfExists('treatment_types');
    }
}
