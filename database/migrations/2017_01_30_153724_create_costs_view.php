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
                ,f.display_name as 'service'
                ,h01.prop_type as 'service_type'
                ,NULL as 'need'
                ,'Weekly' as 'frequency'
                ,h01.start_date
                ,h01.end_date
                ,h01.weekly_cost as 'unit_cost'
            FROM
                clients c
            INNER JOIN
                import_housing_temp_accom h01 ON h01.client_id = c.id
            INNER JOIN
                upload_log u ON u.id = h01.upload_id
            INNER JOIN
                file_types f ON f.id = u.filetype

            UNION

            SELECT
                c.id
                ,c.surname
                ,c.dob
                ,c.postcode
                ,f.display_name as 'service'
                ,asc01.service_type as 'service_type'
                ,asc01.primary_support_reason_category as 'need'
                ,asc01.frequency as 'frequency'
                ,start_date
                ,end_date
                ,asc01.cost as 'unit_cost'
            FROM
                clients c
            INNER JOIN
                import_adult_social_care_services asc01 ON asc01.client_id = c.id
            INNER JOIN
                upload_log u ON u.id = asc01.upload_id
            INNER JOIN
                file_types f ON f.id = u.filetype



            UNION

            SELECT
                c.id
                ,c.surname
                ,c.dob
                ,c.postcode
                ,f.display_name as 'service'
                ,service_desc as 'service_type'
                ,NULL as 'need'
                ,gs.cost_frequency as 'frequency'
                ,start_date
                ,end_date
                ,gs.cost as 'unit_cost'
            FROM
                clients c
            INNER JOIN
                import_general_services gs ON gs.client_id = c.id
            INNER JOIN
                upload_log u ON u.id = gs.upload_id
            INNER JOIN
                file_types f ON f.id = u.filetype



            UNION


            SELECT
                c.id
                ,c.surname
                ,c.dob
                ,c.postcode
                ,f.display_name as 'service'
                ,'Housing Benefit Entitlement' as 'service_type'
                ,NULL as 'need'
                ,'Weekly' as 'frequency'
                ,start_date
                ,end_date
                ,shbe.weekly_housing_benefit_entitlement  as 'unit_cost'
            FROM
                clients c
            INNER JOIN
                import_housing_benefit_entitle shbe ON shbe.client_id = c.id
            INNER JOIN
                upload_log u ON u.id = shbe.upload_id
            INNER JOIN
                file_types f ON f.id = u.filetype");
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
