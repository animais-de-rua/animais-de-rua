<?php

namespace App\Models;

use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Database\Factories\DonationFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Donation extends Model
{
    use CrudTrait;
    /** @use HasFactory<DonationFactory> */
    use HasFactory;

    protected $fillable = [
        'process_id',
        'type',
        'godfather_id',
        'headquarter_id',
        'protocol_id',
        'value',
        'status',
        'date',
        'user_id',
        'notes',
    ];

    /**
     * @return BelongsTo<Process, $this>
     */
    public function process(): BelongsTo
    {
        return $this->belongsTo(Process::class, 'process_id');
    }

    /**
     * @return BelongsTo<Godfather, $this>
     */
    public function godfather(): BelongsTo
    {
        return $this->belongsTo(Godfather::class, 'godfather_id');
    }

    /**
     * @return BelongsTo<Headquarter, $this>
     */
    public function headquarter(): BelongsTo
    {
        return $this->belongsTo(Headquarter::class, 'headquarter_id');
    }

    /**
     * @return BelongsTo<Protocol, $this>
     */
    public function protocol(): BelongsTo
    {
        return $this->belongsTo(Protocol::class, 'protocol_id');
    }

    /**
     * @return BelongsTo<User, $this>
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function getProcessLinkAttribute()
    {
        return $this->getLink($this->process, true, '');
    }

    public function getGodfatherLinkAttribute()
    {
        return $this->getLink($this->godfather, is('admin', 'accountancy'));
    }

    public function getHeadquarterLinkAttribute()
    {
        return $this->getLink($this->headquarter, is('admin', 'accountancy'));
    }

    public function getProtocolLinkAttribute()
    {
        if (! $this->protocol || ! $this->protocol->headquarter) {
            return '-';
        }

        $name = "{$this->protocol->name} ({$this->protocol->headquarter->name})";

        return is('admin', 'accountancy') ? "<a href='/admin/protocol/{$this->protocol->id}/edit'>$name</a>" : $name;
    }

    public function getFullValueAttribute()
    {
        return $this->value.'€';
    }

    public function getUserLinkAttribute()
    {
        return $this->getLink($this->user);
    }
}
