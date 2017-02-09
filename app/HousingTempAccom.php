<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class HousingTempAccom extends Model
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


    /**
     * Fix for SQL Server / Linux: https://github.com/laravel/framework/issues/1756#issuecomment-22780611
     */
    protected function getDateFormat()
    {
        return 'Y-m-d H:i:s';
    }
}
