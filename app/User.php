<?php

namespace App;

use Backpack\Base\app\Notifications\ResetPasswordNotification as ResetPasswordNotification;
use Backpack\CRUD\CrudTrait;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use CrudTrait;
    use HasRoles;
    use Notifiable;

    const ADMIN = 1;
    const VOLUNTEER = 2;
    const STORE = 4;
    const TRANSLATOR = 8;
    const ALL = 15;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
        'phone', 'friend_card_modality', 'status', 'notes',
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

    /*
    |--------------------------------------------------------------------------
    | RELATIONS
    |--------------------------------------------------------------------------
    */

    public function headquarters()
    {
        return $this->belongsToMany('App\Models\Headquarter', 'users_headquarters', 'user_id', 'headquarter_id');
    }

    public function friend_card_modality()
    {
        return $this->belongsTo('App\Models\FriendCardModality', 'friend_card_modality_id');
    }
}
