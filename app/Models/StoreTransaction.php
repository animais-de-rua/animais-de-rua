<?php

namespace App\Models;

use App\User;
use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Database\Factories\StoreTransactionFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StoreTransaction extends Model
{
    use CrudTrait;
    /** @use HasFactory<StoreTransactionFactory> */
    use HasFactory;

    protected $fillable = [
        'description',
        'user_id',
        'amount',
        'invoice',
        'notes',
    ];

    /**
     * @return BelongsTo<User, $this>
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function getUserLinkAttribute()
    {
        return $this->getLink($this->user);
    }

    public function getAmountColorize()
    {
        return $this->colorizeValue($this->amount);
    }

    private function colorizeValue($value)
    {
        if ($value < 0) {
            return "<span style='color:#A00'>{$value}€</span>";
        } else {
            return "<span style='color:#0A0'>{$value}€</span>";
        }
    }
}
