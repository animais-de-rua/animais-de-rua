<?php

namespace App\Models;

use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Database\Factories\VoucherFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Voucher extends Model
{
    use CrudTrait;
    /** @use HasFactory<VoucherFactory> */
    use HasFactory;

    /*
    |--------------------------------------------------------------------------
    | GLOBAL VARIABLES
    |--------------------------------------------------------------------------
    */

    protected $table = 'vouchers';
    protected $primaryKey = 'id';

    // public $timestamps = false;
    // protected $guarded = ['id'];
    protected $fillable = ['reference', 'voucher', 'value', 'percent', 'client_name', 'client_email', 'expiration', 'status'];
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

    public function getValueTextAttribute(): string
    {
        return $this->value ? "{$this->value}€" : '-';
    }

    public function getPercentTextAttribute(): string
    {
        return $this->percent ? "{$this->percent}%" : '-';
    }

    /*
    |--------------------------------------------------------------------------
    | MUTATORS
    |--------------------------------------------------------------------------
    */
}
