<?php

namespace App\Http\Controllers;

use GemaDigital\Http\Controllers\Traits\PageTrait;

class PageController
{
    use PageTrait;

    /**
     * common page
     *
     * @return array<string, mixed>
     */
    public function common(): array
    {
        return [];
    }

    /**
     * home page
     *
     * @return array<string, mixed>
     */
    public function home(): array
    {
        return [];
    }
}
