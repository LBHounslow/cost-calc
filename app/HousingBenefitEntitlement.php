<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class HousingBenefitEntitlement extends \App\TemplateModel
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'import_housing_benefit_entitle';


    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'client_id', 'upload_id', 'claim_ref',
        'address_1', 'address_2', 'address_3', 'address_4', 'postcode',
        'ni', 'title',
        'first_name', 'surname', 'dob',
        'start_date', 'end_date',
        'weekly_housing_benefit_entitlement', 'weekly_eligible_rent_amount', 'contractual_rent_amount',
        'time_period_contractual_rent_figure_covers', 'tenancy_type',
    ];
}
