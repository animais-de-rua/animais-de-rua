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
    const FAT = 4;
    const ALL = 7;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
        'phone', 'headquarter_id', 'friend_card_modality', 'status',
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

    public function headquarter()
    {
        return $this->belongsTo('App\Models\Headquarter', 'headquarter_id');
    }

    public function friend_card_modality()
    {
        return $this->belongsTo('App\Models\FriendCardModality', 'friend_card_modality_id');
    }
}
