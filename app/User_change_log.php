<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class User_change_log extends \App\TemplateModel
{

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'user_change_log';


    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id', 'from', 'to', 'change_user_id',
    ];
    
}
