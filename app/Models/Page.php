<?php

namespace App\Models;

use Backpack\CRUD\app\Models\Traits\SpatieTranslatable\HasTranslations;

class Page extends Model
{
    use HasTranslations;

    /** @var array<string> */
    protected $translatable = [
        'title',
        'extras_translatable',
    ];

    /** @var array<string> */
    protected $fakeColumns = [
        'extras',
        'extras_translatable',
    ];

    #[\Override]
    protected static function booted()
    {
        // static::updated(fn() => Cache::forget('data'));
    }
}
