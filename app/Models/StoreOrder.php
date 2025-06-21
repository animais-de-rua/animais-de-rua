<?php

namespace App\Models;

use App\User;
use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Database\Factories\StoreOrderFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class StoreOrder extends Model
{
    use CrudTrait;
    /** @use HasFactory<StoreOrderFactory> */
    use HasFactory;

    protected $fillable = [
        'reference',
        'cart',
        'recipient',
        'address',
        'user_id',
        'shipment_date',
        'expense',
        'payment',
        'receipt',
        'notes',
        'invoice',
        'status',
    ];

    public function addShipment()
    {
        $disabled = $this->status == 'shipped';

        return '
        <a class="btn btn-xs btn-'.($disabled ? 'default' : 'primary').' '.($disabled ? 'disabled' : '').'" href="/admin/store/orders/'.$this->id.'/edit" title="'.__('Add shipment').'">
        <i class="fa fa-plus"></i> '.ucfirst(__('shipment')).'
        </a>';
    }

    public function openAll()
    {
        return '
        <a class="openall btn btn-primary ladda-button" data-style="zoom-in">
            <span class="ladda-label"><i class="fa fa-plus-square-o"></i> '.ucfirst(__('Open All')).'</span>
        </a>';
    }

    /**
     * @return BelongsTo<User, $this>
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * @return BelongsToMany<StoreProduct, $this>
     */
    public function products(): BelongsToMany
    {
        return $this->belongsToMany(StoreProduct::class, 'store_orders_products', 'store_order_id', 'store_product_id')
            ->withPivot(['quantity', 'discount', 'discount_no_vat']);
    }

    public function getShippedAttribute()
    {
        return $this->status == 'shipped';
    }

    public function getUserLinkAttribute()
    {
        return $this->getLink($this->user, is('admin'));
    }

    public function getTotalSellsValue()
    {
        $sells = data_get_first($this, 'products', 'sells', 0);

        return $sells != 0 ? $sells : '-';
    }

    public function getTotalValue()
    {
        $total = data_get_first($this, 'products', 'total', 0);

        return $total != 0 ? $total : '-';
    }

    public function getNameAttribute()
    {
        return implode(' - ', [$this->reference, $this->recipient]);
    }

    public function toArray()
    {
        $data = parent::toArray();

        $data['name'] = $this->name;

        return $data;
    }
}
