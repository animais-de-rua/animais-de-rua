<?php

namespace App\Models;

use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Database\Factories\StoreTransactionFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class StoreTransaction extends Model
{
    use CrudTrait;
    /** @use HasFactory<StoreTransactionFactory> */
    use HasFactory;

    /*
    |--------------------------------------------------------------------------
    | GLOBAL VARIABLES
    |--------------------------------------------------------------------------
    */

    protected $table = 'store_transactions';
    protected $primaryKey = 'id';
    // public $timestamps = false;
    // protected $guarded = ['id'];
    protected $fillable = ['description', 'user_id', 'amount', 'invoice', 'notes'];
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

    /*
    |--------------------------------------------------------------------------
    | MUTATORS
    |--------------------------------------------------------------------------
    */
}
