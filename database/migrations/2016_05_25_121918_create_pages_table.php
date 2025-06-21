<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreatePagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // TODO: use JSON data type for 'extras' instead of string
        Schema::create('pages', function (Blueprint $table) {
            $table->id();
            $table->string('template');
            $table->string('name');
            $table->string('title');
            $table->string('slug', 127)->unique()->nullable();
            $table->text('content')->nullable();
            $table->longText('extras')->nullable();
            $table->longText('extras_translatable')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('pages');
    }
}
