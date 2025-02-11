<?php

namespace App\Enums;

use ArchTech\Enums\Values;

enum RolesEnum: string
{
    use Values;

    case ADMIN = 'admin';
    case USER = 'user';
}
