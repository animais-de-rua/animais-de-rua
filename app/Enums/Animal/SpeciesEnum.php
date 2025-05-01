<?php

namespace App\Enums\Animal;

use App\Enums\Traits\Translatable;
use ArchTech\Enums\Values;

enum SpeciesEnum: string
{
    use Translatable;
    use Values;

    case DOG = 'dog';
    case CAT = 'cat';
    case OTHER = 'other';
}
