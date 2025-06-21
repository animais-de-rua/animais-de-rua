<?php

namespace App\Models;

use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Database\Factories\AdopterFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Adopter extends Model
{
    use CrudTrait;
    /** @use HasFactory<AdopterFactory> */
    use HasFactory;

    protected $fillable = [
        'name',
        'email',
        'phone',
        'address',
        'zip_code',
        'adoption_date',
        'id_card',
        'territory_id',
        'user_id',
    ];

    /**
     * @return HasOne<Adoption, $this>
     */
    public function adoption(): HasOne
    {
        return $this->hasOne(Adoption::class, 'adopter_id');
    }

    /**
     * @return BelongsTo<Territory, $this>
     */
    public function territory(): BelongsTo
    {
        return $this->belongsTo(Territory::class, 'territory_id');
    }

    /**
     * @return BelongsTo<User, $this>
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // @deprecated
    public function getAdoptionLinkAttribute()
    {
        return $this->getLink($this->adoption);
    }

    // @deprecated
    public function getUserLinkAttribute()
    {
        return $this->getLink($this->user, is('admin'));
    }
}
