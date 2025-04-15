<?php

namespace App\Models;

use App\Models\Headquarter;
use App\Models\Traits\RandomModelTrait;
use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use CrudTrait;
    use HasApiTokens;
    /** @use HasFactory<UserFactory> */
    use HasFactory;
    use HasRoles;
    use Notifiable;
    use RandomModelTrait;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * Headquarters the user belongs to.
     * 
     * @return BelongsToMany<Headquarter, $this>
     */
    public function headquarters(): BelongsToMany
    {
        return $this->belongsToMany(Headquarter::class, 'users_headquarters');
    }
}
