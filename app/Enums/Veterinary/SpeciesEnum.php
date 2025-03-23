<?php

namespace App\Enums\Veterinary;

use ArchTech\Enums\Values;

enum SpeciesEnum: string
{
    use Values;

    case DOG = 'dog';
    case CAT = 'cat';
}
