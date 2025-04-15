<?php

namespace App\Models;

use App\Models\Traits\RandomModelTrait;
use GemaDigital\Models\Model as OriginalModel;

class Model extends OriginalModel
{
    use RandomModelTrait;
}
