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
        'service', 'service_type', 'primary_support_reason_category', 'care_package_line_item_id'
    ];


    /**
     * Get the format for database stored dates.
     *
     * @return string
     */
    public function getDateFormat()
    {
        if (env('DB_CONNECTION', false) == 'mysql') {
            return 'Y-m-d H:i:s';
        } else {
            return 'Y-m-d H:i:s.u';
        }

    }

    /**
     * Convert a DateTime to a storable string.
     * SQL Server will not accept 6 digit second fragment (PHP default: see getDateFormat Y-m-d H:i:s.u)
     * trim three digits off the value returned from the parent.
     *
     * @param  \DateTime|int $value
     * @return string
     */
    public function fromDateTime($value)
    {
        if (env('DB_CONNECTION', false) == 'mysql') {
            return $value;
        } else {
            return substr(parent::fromDateTime($value), 0, -3);
        }

    }
}
