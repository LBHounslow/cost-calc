<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class User_login_log extends Model
{

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'user_login_log';


    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id', 'login_user_name', 'login_user_email', 'login_client_ip'
    ];

    /**
     * Fix for SQL Server / Linux: https://github.com/laravel/framework/issues/1756#issuecomment-22780611
     */
    protected function getDateFormat()
    {
        return 'Y-m-d H:i:s';
    }
}
