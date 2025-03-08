<?php

use App\Helpers\EnumHelper;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGodfathersTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('godfathers', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name', 255);
            $table->string('alias', 255)->nullable();
            $table->string('email', 127)->nullable();
            $table->string('phone', 255)->nullable();
            $table->text('notes')->nullable();
            $table->string('territory_id', 6)->nullable();
            $table->integer('user_id')->nullable()->unsigned();

            $table->index(['territory_id']);
            $table->foreign('territory_id')
                ->references('id')
                ->on('territories')
                ->onDelete('set null');

            $table->index(['user_id']);
            $table->foreign('user_id')
                ->references('id')
                ->on('users')
                ->onDelete('set null');

            $table->timestamps();
        });

        Schema::create('donations', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('process_id')->nullable()->unsigned();
            $table->integer('user_id')->nullable()->unsigned();

            $table->enum('type', EnumHelper::values('donation.type'));
            $table->integer('godfather_id')->nullable()->unsigned();
            $table->integer('headquarter_id')->nullable()->unsigned();
            $table->integer('protocol_id')->nullable()->unsigned();

            $table->decimal('value', 8, 2)->nullable()->unsigned()->default(0);
            $table->date('date')->nullable();
            $table->text('notes')->nullable();

            $table->index(['process_id']);
            $table->foreign('process_id')
                ->references('id')
                ->on('processes')
                ->onDelete('set null');

            $table->index(['godfather_id']);
            $table->foreign('godfather_id')
                ->references('id')
                ->on('godfathers')
                ->onDelete('set null');

            $table->index(['user_id']);
            $table->foreign('user_id')
                ->references('id')
                ->on('users')
                ->onDelete('set null');

            $table->index(['headquarter_id']);
            $table->foreign('headquarter_id')
                ->references('id')
                ->on('headquarters')
                ->onDelete('set null');

            $table->index(['protocol_id']);
            $table->foreign('protocol_id')
                ->references('id')
                ->on('protocols')
                ->onDelete('set null');

            // $table->unique(['process_id', 'godfather_id']);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::dropIfExists('donations');
        Schema::dropIfExists('godfathers');
    }
}
