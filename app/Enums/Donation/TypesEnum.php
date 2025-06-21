<?php

namespace App\Enums\Donation;

use ArchTech\Enums\Values;

enum TypesEnum: string
{
    use Values;

    case PRIVATE = 'private';
    case HEADQUARTER = 'headquarter';
    case PROTOCOL = 'protocol';
}
