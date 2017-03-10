<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TroubledFamilies extends \App\TemplateModel
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'import_troubled_families';


    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'client_id', 'upload_id', 'individual_id', 'family_id',
        'first_name', 'surname', 'dob',
        'address_1', 'address_2', 'postcode', 'uprn', 'local_uprn',
    ];

}
