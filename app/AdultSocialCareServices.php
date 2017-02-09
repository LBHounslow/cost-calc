<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AdultSocialCareServices extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'import_adult_social_care_services';


    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'client_id', 'upload_id', 'asc_id',
        'address_1', 'address_2', 'address_3', 'town', 'county', 'postcode',
        'nhs_no', 'first_name', 'surname', 'dob',
        'start_date', 'end_date', 'cost', 'frequency',
        'service', 'service_type', 'primary_support_reason_category'
    ];


    /**
     * Fix for SQL Server / Linux: https://github.com/laravel/framework/issues/1756#issuecomment-22780611
     */
    protected function getDateFormat()
    {
        return 'Y-m-d H:i:s';
    }
}
