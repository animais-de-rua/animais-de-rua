<?php

namespace App\Models;

use Backpack\CRUD\CrudTrait;

class StoreStock extends Model
{
    use CrudTrait;

    /*
    |--------------------------------------------------------------------------
    | GLOBAL VARIABLES
    |--------------------------------------------------------------------------
    */

    protected $table = 'store_stock';
    protected $primaryKey = 'id';
    // public $timestamps = false;
    // protected $guarded = ['id'];
    protected $fillable = ['user_id', 'store_product_id', 'quantity', 'type', 'notes'];
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

    public function user()
    {
        return $this->belongsTo('App\User', 'user_id');
    }

    public function product()
    {
        return $this->belongsTo('App\Models\StoreProduct', 'store_product_id');
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

    /*
    |--------------------------------------------------------------------------
    | MUTATORS
    |--------------------------------------------------------------------------
    */
}
