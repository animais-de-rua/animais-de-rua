<?php

namespace App\Models;

use App\User;
use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Database\Factories\ProtocolRequestFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProtocolRequest extends Model
{
    use CrudTrait;
    /** @use HasFactory<ProtocolRequestFactory> */
    use HasFactory;

    protected $fillable = [
        'council',
        'name',
        'email',
        'phone',
        'address',
        'description',
        'territory_id',
        'user_id',
        'protocol_id',
    ];

    /**
     * @return BelongsTo<Territory, $this>
     */
    public function territory(): BelongsTo
    {
        return $this->belongsTo(Territory::class, 'territory_id');
    }

    /**
     * @return BelongsTo<Process, $this>
     */
    public function process(): BelongsTo
    {
        return $this->belongsTo(Process::class, 'process_id');
    }

    /**
     * @return BelongsTo<User, $this>
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * @return BelongsTo<Protocol, $this>
     */
    public function protocol(): BelongsTo
    {
        return $this->belongsTo(Protocol::class, 'protocol_id');
    }

    public function getUserLinkAttribute()
    {
        return $this->getLink($this->user, is('admin'));
    }

    public function getProcessLinkAttribute()
    {
        return $this->getLink($this->process, true, '');
    }

    public function getProtocolLinkAttribute()
    {
        return $this->getLink($this->protocol, is('admin', 'protocols'));
    }
}
