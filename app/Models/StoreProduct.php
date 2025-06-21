<?php

namespace App\Models;

use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Database\Factories\StoreProductFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class StoreProduct extends Model
{
    use CrudTrait;
    /** @use HasFactory<StoreProductFactory> */
    use HasFactory;

    protected $fillable = [
        'name',
        'price',
        'price_no_vat',
        'expense',
        'notes',
        'vat',
    ];

    /**
     * @return BelongsToMany<StoreOrder, $this>
     */
    public function orders(): BelongsToMany
    {
        return $this->belongsToMany(StoreOrder::class, 'store_orders_products', 'store_product_id', 'store_order_id')
            ->withPivot('quantity');
    }

    public function getTotalSellsValue()
    {
        $sells = data_get_first($this, 'orders', 'sells', 0);

        return $sells != 0 ? $sells : '-';
    }

    public function getShipmentExpenseValue()
    {
        $shipment_expense = data_get_first($this, 'orders', 'shipment_expense', 0);

        return $shipment_expense != 0 ? number_format($shipment_expense, 2) : '-';
    }

    public function getTotalProfitValue()
    {
        $sells = data_get_first($this, 'orders', 'sells', 0);
        $shipment_expense = data_get_first($this, 'orders', 'shipment_expense', 0);

        return number_format($sells * ($this->price_no_vat - $this->expense) - $shipment_expense, 2);
    }
}
