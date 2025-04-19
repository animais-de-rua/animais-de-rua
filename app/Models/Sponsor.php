<?php

namespace App\Models;

use Backpack\CRUD\app\Models\Traits\CrudTrait;

class Sponsor extends Model
{
    use CrudTrait;

    protected $fillable = [
        'name',
        'url',
        'image',
    ];

    public function setImageAttribute($value): void
    {
        $filename = $this->attributes['name'].time();
        $this->saveImage($this, $value, 'sponsors/', $filename, 144, 90);
    }
}
