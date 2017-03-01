<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCostsView extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("CREATE VIEW Costs
            AS
            SELECT
                c.id
                ,c.surname
                ,c.dob
                ,c.postcode
                ,'Temporary Accommodation' as 'service'
                ,h01.prop_type as 'service_type'
                ,'Weekly' as 'frequency'
                ,h01.start_date
                ,h01.end_date
                ,h01.weekly_cost as 'unit_cost'
            FROM
                clients c
            INNER JOIN
                import_housing_temp_accom h01 ON h01.client_id = c.id

            UNION

            SELECT
                c.id
                ,c.surname
                ,c.dob
                ,c.postcode
                ,'Adult Social Care' as 'service'
                ,COALESCE(asc01.primary_support_reason_category, asc01.service_type) as 'service_type'
                ,asc01.frequency as 'frequency'
                ,start_date
                ,end_date
                ,asc01.cost as 'unit_cost'
            FROM
                clients c
            INNER JOIN
                import_adult_social_care_services asc01 ON asc01.client_id = c.id");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement("DROP VIEW Costs");
    }
}
