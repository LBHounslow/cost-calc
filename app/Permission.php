<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Permission extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'permissions';


    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'permission', 'display_name'
    ];


    /**
     * Fix for SQL Server / Linux: https://github.com/laravel/framework/issues/1756#issuecomment-22780611
     */
    protected function getDateFormat()
    {
        return 'Y-m-d H:i:s';
    }
}
