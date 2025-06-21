<?php

namespace App\Enums\Veterinary;

use ArchTech\Enums\Values;

enum StatusEnum: string
{
    use Values;

    case ACTIVE = 'active';
    case INACTIVE = 'inactive';
}
