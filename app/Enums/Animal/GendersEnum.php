<?php

namespace App\Enums\Animal;

use ArchTech\Enums\Values;

enum GendersEnum: string
{
    use Values;

    case MALE = 'male';
    case FEMALE = 'female';
}
