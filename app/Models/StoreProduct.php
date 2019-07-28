<?php

namespace App\Models;

use Backpack\CRUD\CrudTrait;

class StoreProduct extends Model
{
    use CrudTrait;

    /*
    |--------------------------------------------------------------------------
    | GLOBAL VARIABLES
    |--------------------------------------------------------------------------
    */

    protected $table = 'store_products';
    // protected $primaryKey = 'id';
    // public $timestamps = false;
    // protected $guarded = ['id'];
    protected $fillable = ['name', 'price', 'price_no_vat', 'expense', 'notes'];
    // protected $hidden = [];
    // protected $dates = [];

    /*
    |--------------------------------------------------------------------------
    | FUNCTIONS
    |--------------------------------------------------------------------------
    */

    /*
    |--------------------------------------------------------------------------
    | RELATIONS
    |--------------------------------------------------------------------------
    */

    public function orders()
    {
        return $this->belongsToMany('App\Models\StoreOrder', 'store_orders_products', 'store_product_id', 'store_order_id')->withPivot('quantity');
    }

    /*
    |--------------------------------------------------------------------------
    | SCOPES
    |--------------------------------------------------------------------------
    */

    /*
    |--------------------------------------------------------------------------
    | ACCESORS
    |--------------------------------------------------------------------------
    */

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

    /*
    |--------------------------------------------------------------------------
    | MUTATORS
    |--------------------------------------------------------------------------
    */
}
