<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Permission extends \App\TemplateModel
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


}
