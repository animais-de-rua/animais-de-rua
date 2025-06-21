<?php

namespace App\Models;

use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Headquarter extends Model
{
    use CrudTrait;

    protected $fillable = [
        'name',
        'address',
        'phone',
        'mail',
        'description',
        'active',
    ];

    /**
     * @return BelongsToMany<Territory, $this>
     */
    public function territories(): BelongsToMany
    {
        return $this->belongsToMany(Territory::class, 'headquarters_territories', 'headquarter_id', 'territory_id');
    }

    /**
     * @return BelongsToMany<Territory, $this>
     */
    public function territories_range(): BelongsToMany
    {
        return $this->belongsToMany(Territory::class, 'headquarters_territories_range', 'headquarter_id', 'territory_id');
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('active', 1);
    }
}
