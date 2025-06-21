<?php

namespace App\Models;

use App\User;
use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Spatie\Translatable\HasTranslations;

class Partner extends Model
{
    use CrudTrait;
    use HasTranslations;

    protected $fillable = [
        'name',
        'description',
        'email',
        'phone1',
        'phone1_info',
        'phone2',
        'phone2_info',
        'url',
        'facebook',
        'instagram',
        'address',
        'address_info',
        'latlong',
        'benefit',
        'notes',
        'promo_code',
        'status',
        'user_id',
        'image',
    ];
    protected $translatable = [
        'benefit',
    ];

    /**
     * @return BelongsToMany<Territory, $this>
     */
    public function territories(): BelongsToMany
    {
        return $this->belongsToMany(Territory::class, 'partners_territories', 'partner_id', 'territory_id');
    }

    /**
     * @return BelongsToMany<PartnerCategory, $this>
     */
    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(PartnerCategory::class, 'partners_categories', 'partner_id', 'partner_category_list_id');
    }

    /**
     * @return BelongsTo<User, $this>
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function getUserLinkAttribute()
    {
        return $this->getLink($this->user, is('admin'));
    }

    public function getCategoryListAttribute(): string
    {
        return implode(', ', $this->categories()->pluck('name')->toArray());
    }

    public function getTerritoryListAttribute(): string
    {
        return implode(', ', $this->territories()->pluck('name')->toArray());
    }

    public function setImageAttribute($value): void
    {
        $filename = $this->attributes['name'];
        $this->saveImage($this, $value, 'partners/', $filename, 192, 82);
    }
}
