<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class HousingBenefitSwitch extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'import_housing_benefit_switch';


    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'client_id', 'upload_id', 'claim_no',
        'ni', 'title', 'first_name', 'surname', 'dob',
        'postcode', 'address'
    ];

}
