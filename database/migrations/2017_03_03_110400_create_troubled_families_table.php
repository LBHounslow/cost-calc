<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTroubledFamiliesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('import_troubled_families', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('client_id')->nullable();
            $table->integer('upload_id');
            $table->string('individual_id', 200)->nullable();
            $table->string('family_id', 200)->nullable();
            $table->string('first_name', 200)->nullable();
            $table->string('surname', 200)->nullable();
            $table->date('dob')->nullable();
            $table->string('address_1', 200)->nullable();
            $table->string('address_2', 200)->nullable();
            $table->string('postcode', 200)->nullable();
            $table->string('uprn', 200)->nullable();
            $table->string('local_uprn', 200)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('import_troubled_families');
    }
}
