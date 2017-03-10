<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Upload_log extends \App\TemplateModel
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
        'original_filename', 'path', 'filetype', 'user_id', 'processed', 'status', 'msg', 'deleted',
    ];


    public function fileType()
    {
        return $this->hasOne('App\FileType', 'id', 'filetype');
    }

}
