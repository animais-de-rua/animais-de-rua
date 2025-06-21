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

    protected $fillable = [
        'reference',
        'voucher',
        'value',
        'percent',
        'client_name',
        'client_email',
        'expiration',
        'status',
    ];

    public function getValueTextAttribute(): string
    {
        return $this->value ? "{$this->value}€" : '-';
    }

    public function getPercentTextAttribute(): string
    {
        return $this->percent ? "{$this->percent}%" : '-';
    }
}
