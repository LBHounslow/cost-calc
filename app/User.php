<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'permissions', 'status', 'provider_code',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * Checks whether current user is an admin.
     *
     * @return void
     */
    public function isAdmin()
    {
        return ($this->admin == 1);

    }

    public function getAllowedFileTypes()
    {
        return json_decode($this->provider->allowed_file_types);
    }

    public function getPermissions()
    {
        return json_decode($this->permissions);
    }

    public function hasPermission($permission)
    {
        if (in_array($permission, $this->getPermissions())) {
            return true;
        } else {
            return false;
        }
    }


    public function provider()
    {
        return $this->hasOne('App\Provider', 'code', 'provider_code');
    }

    /**
     * Fix for SQL Server / Linux: https://github.com/laravel/framework/issues/1756#issuecomment-22780611
     */
    protected function getDateFormat()
    {
        return 'Y-m-d H:i:s';
    }
}
