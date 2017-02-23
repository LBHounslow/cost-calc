<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAdultSocialCareServicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('import_adult_social_care_services', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('client_id')->nullable();
            $table->integer('upload_id');
            $table->string('asc_id', 200)->nullable();
            $table->string('address_1', 200)->nullable();
            $table->string('address_2', 200)->nullable();
            $table->string('address_3', 200)->nullable();
            $table->string('town', 200)->nullable();
            $table->string('county', 200)->nullable();
            $table->string('postcode', 200)->nullable();
            $table->string('nhs_no', 200)->nullable();
            $table->string('first_name', 200)->nullable();
            $table->string('surname', 200)->nullable();
            $table->date('dob')->nullable();
            $table->float('cost', 8, 2)->nullable();
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->string('frequency', 200)->nullable();
            $table->string('service', 200)->nullable();
            $table->string('service_type', 200)->nullable();
            $table->string('primary_support_reason_category', 200)->nullable();
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
        Schema::dropIfExists('import_adult_social_care_services');
    }
}
