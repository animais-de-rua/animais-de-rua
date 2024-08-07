<?php

use Illuminate\Database\Migrations\Migration;

class UpdatePetsittingRoleEnum extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("ALTER TABLE users MODIFY COLUMN petsitting_role ENUM('Dog', 'Cat', 'Both', 'Others')");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement("ALTER TABLE users MODIFY COLUMN petsitting_role ENUM('Dog', 'Cat', 'Both')");
    }
}
