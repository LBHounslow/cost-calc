<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateHousingBenefitEntitlementsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('import_housing_benefit_entitle', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('client_id')->nullable()->index();
            $table->integer('upload_id');
            $table->string('claim_ref', 200)->nullable();
            $table->string('address_1', 200)->nullable();
            $table->string('address_2', 200)->nullable();
            $table->string('address_3', 200)->nullable();
            $table->string('address_4', 200)->nullable();
            $table->string('postcode', 200)->nullable();
            $table->string('ni', 200)->nullable();
            $table->string('title', 200)->nullable();
            $table->string('first_name', 200)->nullable();
            $table->string('surname', 200)->nullable();
            $table->date('dob')->nullable();
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->float('weekly_housing_benefit_entitlement', 8, 2)->nullable();
            $table->float('weekly_eligible_rent_amount', 8, 2)->nullable();
            $table->float('contractual_rent_amount', 8, 2)->nullable();
            $table->string('time_period_contractual_rent_figure_covers', 200)->nullable();
            $table->string('tenancy_type', 200)->nullable();
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
        Schema::dropIfExists('import_housing_benefit_entitle');
    }
}
