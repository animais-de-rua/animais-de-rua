<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use App\Helpers\EnumHelper;

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
            $table->integer('process_id')->unsigned();
            $table->integer('user_id')->unsigned();
            $table->integer('fat_id')->unsigned();
            $table->string('name', 255);
            $table->text('history')->nullable();
            $table->timestamps();

            $table->index(['process_id']);
            $table->foreign('process_id')
                ->references('id')
                ->on('processes')
                ->onDelete('cascade');

            $table->index(['user_id']);
            $table->foreign('user_id')
                ->references('id')
                ->on('users')
                ->onDelete('cascade');

            $table->index(['fat_id']);
            $table->foreign('fat_id')
                ->references('id')
                ->on('users')
                ->onDelete('cascade');
        });

        Schema::create('animals', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('adoption_id')->unsigned();
            $table->string('name', 255);
            $table->integer('age')->unsigned()->default(0);
            $table->enum('gender', EnumHelper::values('animal.gender'))->nullable();
            $table->boolean('sterilized')->default(0);
            $table->boolean('vaccinated')->default(0);
            $table->timestamps();

            $table->index(['adoption_id']);
            $table->foreign('adoption_id')
                ->references('id')
                ->on('adoptions')
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
        Schema::dropIfExists('animals');
        Schema::dropIfExists('adoptions');
    }
}
