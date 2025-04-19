<?php

namespace App\Models;

use App\User;
use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Database\Factories\FatFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Fat extends Model
{
    use CrudTrait;
    /** @use HasFactory<FatFactory> */
    use HasFactory;

    protected $fillable = [
        'name',
        'email',
        'phone',
        'territory_id',
        'user_id',
    ];

    /**
     * @return HasMany<Adoption, $this>
     */
    public function adoptions(): HasMany
    {
        return $this->hasMany(Adoption::class, 'fat_id');
    }

    /**
     * @return BelongsTo<Territory, $this>
     */
    public function territory(): BelongsTo
    {
        return $this->belongsTo(Territory::class, 'territory_id');
    }

    /**
     * @return BelongsToMany<Headquarter, $this>
     */
    public function headquarters(): BelongsToMany
    {
        return $this->belongsToMany(Headquarter::class, 'fats_headquarters', 'fat_id', 'headquarter_id');
    }

    /**
     * @return BelongsTo<User, $this>
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function getDetailAttribute()
    {
        return "{$this->name} ({$this->email})";
    }

    public function getUserLinkAttribute()
    {
        return $this->getLink($this->user, is('admin'));
    }

    public function toArray()
    {
        $data = parent::toArray();

        $data['detail'] = $this->detail;

        return $data;
    }
}
