<?php

namespace App\Models;

use App\User;
use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Database\Factories\StoreStockFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StoreStock extends Model
{
    use CrudTrait;
    /** @use HasFactory<StoreStockFactory> */
    use HasFactory;

    protected $fillable = [
        'user_id',
        'store_product_id',
        'quantity',
        'type',
        'notes',
    ];

    /**
     * @return BelongsTo<User, $this>
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * @return BelongsTo<StoreProduct, $this>
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(StoreProduct::class, 'store_product_id');
    }

    public function getUserLinkAttribute()
    {
        return $this->getLink($this->user);
    }

    public function getProductLinkAttribute()
    {
        return $this->getLink($this->product);
    }

    public function getQuantityColorize()
    {
        return $this->colorizeValue($this->quantity);
    }

    private function colorizeValue($value)
    {
        if ($value < 0) {
            return "<span style='color:#A00'>{$value}</span>";
        } else {
            return "<span style='color:#0A0'>{$value}</span>";
        }
    }
}
