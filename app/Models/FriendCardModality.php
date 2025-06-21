<?php

namespace App\Models;

use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\Translatable\HasTranslations;

class FriendCardModality extends Model
{
    use CrudTrait;
    use HasTranslations;

    protected $fillable = [
        'name',
        'description',
        'paypal_code',
        'amount',
        'type',
        'visible',
    ];
    protected $translatable = [
        'name',
        'description',
    ];

    /**
     * @return HasMany<User, $this>
     */
    public function user(): HasMany
    {
        return $this->hasMany(User::class, 'friend_card_modality_id');
    }

    public function getFullnameAttribute(): string
    {
        $type = ucfirst(__($this->type));

        return "{$this->name} — {$this->amount}€ {$type}";
    }

    public function getValueAttribute(): string
    {
        $type = ucfirst(__($this->type));

        return "{$this->amount}€ {$type}";
    }
}
