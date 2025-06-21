<?php

namespace App\Models;

use App\User;
use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Database\Factories\ProtocolFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Protocol extends Model
{
    use CrudTrait;
    /** @use HasFactory<ProtocolFactory> */
    use HasFactory;

    protected $fillable = [
        'name',
        'email',
        'phone',
        'territory_id',
        'user_id',
    ];

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

    /**
     * @return BelongsTo<Headquarter, $this>
     */
    public function headquarter(): BelongsTo
    {
        return $this->belongsTo(Headquarter::class, 'headquarter_id');
    }

    public function getUserLinkAttribute()
    {
        return $this->getLink($this->user, is('admin'));
    }
}
