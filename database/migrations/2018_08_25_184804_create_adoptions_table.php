<?php

use App\Helpers\EnumHelper;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAdoptionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('adoptions', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('process_id')->nullable()->unsigned();
            $table->integer('user_id')->nullable()->unsigned();
            $table->integer('fat_id')->nullable()->unsigned();

            // Animal
            $table->string('name', 255);
            $table->string('name_after', 255)->nullable();
            $table->integer('age')->unsigned()->default(0);
            $table->enum('gender', EnumHelper::values('animal.gender'))->nullable();
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
            $table->integer('adopter_id')->unsigned()->nullable();
            $table->enum('status', EnumHelper::values('adoption.status'))->default('open');
            $table->timestamps();

            $table->index(['process_id']);
            $table->foreign('process_id')
                ->references('id')
                ->on('processes')
                ->onDelete('set null');

            $table->index(['user_id']);
            $table->foreign('user_id')
                ->references('id')
                ->on('users')
                ->onDelete('set null');

            $table->index(['fat_id']);
            $table->foreign('fat_id')
                ->references('id')
                ->on('fats')
                ->onDelete('set null');

            $table->index(['adopter_id']);
            $table->foreign('adopter_id')
                ->references('id')
                ->on('adopters')
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
        Schema::dropIfExists('adoptions');
    }
}
