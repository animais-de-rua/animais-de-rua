<?php

namespace App\Models;

use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Spatie\Translatable\HasTranslations;

class Campaign extends Model
{
    use CrudTrait;
    use HasTranslations;

    protected $fillable = [
        'name',
        'introduction',
        'description',
        'image',
    ];
    protected $translatable = [
        'name',
        'introduction',
        'description',
    ];

    // @deprecated
    public function setImageAttribute($value)
    {
        $filename = json_decode($this->attributes['name'])->pt;
        $this->saveImage($this, $value, 'campaigns/', $filename, 800, 88);
    }
}
