<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Provider extends \App\TemplateModel
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'providers';


    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'display_name', 'allowed_file_types', 'code', 'allowed_file_types',
    ];


}
