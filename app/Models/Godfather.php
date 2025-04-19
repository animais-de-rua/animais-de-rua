<?php

namespace App\Models;

use App\User;
use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Database\Factories\GodfatherFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Godfather extends Model
{
    use CrudTrait;
    /** @use HasFactory<GodfatherFactory> */
    use HasFactory;

    protected $fillable = [
        'name',
        'email',
        'phone',
        'notes',
        'territory_id',
        'user_id',
    ];

    /**
     * @return HasMany<Donation, $this>
     */
    public function donations(): HasMany
    {
        return $this->hasMany(Donation::class, 'godfather_id');
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
        return $this->belongsToMany(Headquarter::class, 'godfathers_headquarters', 'godfather_id', 'headquarter_id');
    }

    /**
     * @return BelongsTo<User, $this>
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function getTotalDonatedValue()
    {
        $donations = data_get_first($this, 'donations', 'total', 0);

        return $donations != 0 ? $donations.'€' : '-';
    }

    public function getDetailAttribute()
    {
        return "{$this->name} ({$this->email})";
    }

    public function getTotalDonatedStats()
    {
        $donations = $this->donations->reduce(function ($carry, $item) {
            return $carry + $item->value;
        });

        return $donations != 0 ? $donations.'€' : '-';
    }

    public function getTotalDonationsStats()
    {
        return count($this->donations);
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
