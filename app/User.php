<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Backpack\CRUD\CrudTrait;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Backpack\Base\app\Notifications\ResetPasswordNotification as ResetPasswordNotification;

class User extends Authenticatable
{
    use CrudTrait;
    use HasRoles;
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
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
     * Send the password reset notification.
     *
     * @param  string  $token
     * @return void
     */
    public function sendPasswordResetNotification($token)
    {
        $this->notify(new ResetPasswordNotification($token));
    }

    /**
     * User Roles
     */
    public function has_role($roles) {
        if(is_string($roles)) {
            return $this->roles->contains('name', $roles);
        }
        else if(is_array($roles)) {
            foreach ($roles as $role)
                if($this->roles->contains('name', $role))
                    return true;
            return false;
        }
    }

    public function is_admin() {
        return $this->has_role('admin') || $this->has_role('superadmin');
    }

    public function is_superadmin() {
        return $this->has_role('superadmin');
    }
}
