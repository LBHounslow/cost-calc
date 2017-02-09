<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class HousingSHBE extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'import_housing_shbe';


    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'upload_id', 'nino', 'surname', 'firstname', 'dob',
        'address1', 'address2', 'address3', 'address4', 'postcode',
        'housingbenefitentitlement', 'eligiblerentamount', 'contracturalrentamount',
        'startdate', 'enddate',
    ];

    /**
     * Fix for SQL Server / Linux: https://github.com/laravel/framework/issues/1756#issuecomment-22780611
     */
    protected function getDateFormat()
    {
        return 'Y-m-d H:i:s';
    }
}
