<?php

use App\Enums\Adoption\StatusEnum;
use App\Enums\Animal\GendersEnum;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAdoptionsTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('adoptions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('process_id')->nullable()->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('cascade');
            $table->foreignId('fat_id')->nullable()->constrained()->onDelete('cascade');

            // Animal
            $table->string('name', 255);
            $table->string('name_after', 255)->nullable();
            $table->integer('age')->unsigned()->default(0);
            $table->enum('gender', GendersEnum::values())->nullable();
            $table->string('microchip')->nullable();
            $table->boolean('sterilized')->default(0);
            $table->boolean('vaccinated')->default(0);
            $table->boolean('processed')->default(0);
            $table->boolean('individual')->default(0);
            $table->boolean('docile')->default(0);
            $table->boolean('abandoned')->default(0);
            $table->boolean('foal')->default(0);
            $table->text('images')->nullable();
            $table->text('features')->nullable();
            $table->text('history')->nullable();
            $table->date('adoption_date');
            $table->foreignId('adopter_id')->nullable()->constrained()->onDelete('cascade');
            $table->enum('status', StatusEnum::values())->default(StatusEnum::OPEN->value);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('adoptions');
    }
}
