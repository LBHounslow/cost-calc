<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateHousingBenefitSwitchesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('import_housing_benefit_switch', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('client_id')->nullable()->index();
            $table->integer('upload_id');
            $table->integer('claim_no')->nullable();
            $table->string('ni', 200)->nullable();
            $table->string('title', 200)->nullable();
            $table->string('first_name', 200)->nullable();
            $table->string('surname', 200)->nullable();
            $table->date('dob')->nullable();
            $table->string('postcode', 200)->nullable();
            $table->string('address', 200)->nullable();
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
        Schema::dropIfExists('import_housing_benefit_switch');
    }
}
