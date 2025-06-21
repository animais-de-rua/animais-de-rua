<?php

namespace App\Models;

use GemaDigital\Models\Model as OriginalModel;
use Override;

class Model extends OriginalModel
{
    /**
     * Automatically eager load relationships when the model is retrieved.
     */
    #[Override]
    protected static function booted()
    {
        self::automaticallyEagerLoadRelationships();
    }
}
