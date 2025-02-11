<?php

namespace App\Models;

use Backpack\CRUD\app\Models\Traits\SpatieTranslatable\HasTranslations;
use Backpack\PageManager\app\Models\Page as OriginalPage;

class Page extends OriginalPage
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
