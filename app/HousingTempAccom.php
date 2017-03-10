<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class HousingTempAccom extends \App\TemplateModel
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'import_housing_temp_accom';


    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'client_id', 'upload_id', 'pin',
        'address_1', 'address_2', 'address_3', 'address_4', 'postcode',
        'ni', 'first_name', 'surname', 'dob',
        'residents', 'start_date', 'end_date', 'weekly_cost',
        'prop_type', 'prop_sub_type'
    ];

}
