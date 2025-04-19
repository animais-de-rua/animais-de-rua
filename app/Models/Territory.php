<?php

namespace App\Models;

use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Database\Factories\TerritoryFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Territory extends Model
{
    use CrudTrait;
    /** @use HasFactory<TerritoryFactory> */
    use HasFactory;

    public $timestamps = false;
    protected $fillable = [];
    protected $hidden = ['pivot'];
    protected $casts = [
        'id' => 'string',
    ];

    const DISTRITO = 1;
    const CONCELHO = 2;
    const FREGUESIA = 4;
    const ALL = 7;

    /**
     * @return HasMany<Territory, $this>
     */
    public function children(): HasMany
    {
        return $this->hasMany(Territory::class, 'parent_id');
    }

    /**
     * @return BelongsTo<Territory, $this>
     */
    public function parent(): BelongsTo
    {
        return $this->belongsTo(Territory::class, 'parent_id');
    }

    public function scopeDistrict($query)
    {
        return $query->where('level', 1);
    }

    public function scopeCounty($query)
    {
        return $query->where('level', 2);
    }

    public function scopeParish($query)
    {
        return $query->where('level', 3);
    }

    public function getFullnameAttribute()
    {
        return $this->name.($this->parent()->exists() ? ', '.$this->parent()->first()->fullname : '');
    }
}
