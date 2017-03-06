<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGeneralServicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('import_general_services', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('client_id')->nullable()->index();
            $table->integer('upload_id');
            $table->string('ext_ref', 200)->nullable();
            $table->string('address_1', 200)->nullable();
            $table->string('address_2', 200)->nullable();
            $table->string('address_3', 200)->nullable();
            $table->string('address_4', 200)->nullable();
            $table->string('postcode', 200)->nullable();
            $table->string('ni', 200)->nullable();
            $table->string('nhs_no', 200)->nullable();
            $table->string('first_name', 200)->nullable();
            $table->string('surname', 200)->nullable();
            $table->date('dob')->nullable();
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->float('cost', 8, 2)->nullable();
            $table->string('cost_frequency', 200)->nullable();
            $table->string('service_desc', 200)->nullable();
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
        Schema::dropIfExists('import_general_services');
    }
}
