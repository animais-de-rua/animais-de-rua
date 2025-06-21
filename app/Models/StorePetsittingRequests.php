<?php

namespace App\Models;

use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Illuminate\Database\Eloquent\Model;

class StorePetsittingRequests extends Model
{
    use CrudTrait;

    protected $fillable = [
        'name',
    ];
}
