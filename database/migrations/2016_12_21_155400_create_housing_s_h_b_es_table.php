<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateHousingSHBEsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('import_housing_shbe', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('client_id')->nullable();
            $table->integer('upload_id');
            $table->string('nino', 200)->nullable();
            $table->string('surname', 200)->nullable();
            $table->string('firstname', 200)->nullable();
            $table->date('dob')->nullable();
            $table->string('address1', 200)->nullable();
            $table->string('address2', 200)->nullable();
            $table->string('address3', 200)->nullable();
            $table->string('address4', 200)->nullable();
            $table->string('postcode', 200)->nullable();
            $table->float('housingbenefitentitlement', 8, 2)->nullable();
            $table->float('eligiblerentamount', 8, 2)->nullable();
            $table->float('contracturalrentamount', 8, 2)->nullable();
            $table->date('startdate')->nullable();
            $table->date('enddate')->nullable();
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
        Schema::dropIfExists('import_housing_shbe');
    }
}
