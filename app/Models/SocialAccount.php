<?php

namespace App\Models;

use App\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SocialAccount extends Model
{
    protected $table = 'users_social_accounts';
    protected $primaryKey = [
        'user_id',
        'provider',
    ];
    protected $fillable = [
        'user_id',
        'provider_user_id',
        'provider',
        'token',
    ];
    public $incrementing = false;

    /**
     * @return BelongsTo<User, $this>
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
