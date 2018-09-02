<?php

namespace App\Models;

use App\Helpers\CompositeKeyHelper;
use App\User;
use Illuminate\Database\Eloquent\Model;

class SocialAccount extends Model
{
    use CompositeKeyHelper;

    protected $fillable = ['user_id', 'provider_user_id', 'provider', 'token'];

    protected $table = 'users_social_accounts';

    protected $primaryKey = ['user_id', 'provider'];

    public $incrementing = false;

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
