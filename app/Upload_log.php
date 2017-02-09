<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Upload_log extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'upload_log';


    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'original_filename', 'path', 'filetype', 'user_id', 'processed', 'status', 'error_msg', 'deleted',
    ];


    /**
     * Fix for SQL Server / Linux: https://github.com/laravel/framework/issues/1756#issuecomment-22780611
     */
    protected function getDateFormat()
    {
        return 'Y-m-d H:i:s';
    }
}
