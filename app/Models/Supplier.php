<?php

namespace App\Models;

use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Database\Factories\SupplierFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Supplier extends Model
{
    use CrudTrait;
    /** @use HasFactory<SupplierFactory> */
    use HasFactory;

    protected $fillable = [
        'reference',
        'store_order_id',
        'store_product_id',
        'invoice',
        'notes',
        'status',
    ];

    /**
     * @return BelongsTo<StoreOrder, $this>
     */
    public function order(): BelongsTo
    {
        return $this->belongsTo(StoreOrder::class, 'store_order_id');
    }

    /**
     * @return BelongsTo<StoreProduct, $this>
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(StoreProduct::class, 'store_product_id');
    }

    public function getProductLinkAttribute()
    {
        return $this->getLink($this->product);
    }

    public function getOrderLinkAttribute()
    {
        return $this->getLink($this->order, true, 'edit', 'name');
    }
}
