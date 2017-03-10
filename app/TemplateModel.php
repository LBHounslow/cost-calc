<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TemplateModel extends Model
{

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
