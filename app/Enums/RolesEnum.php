<?php

namespace App\Enums;

use ArchTech\Enums\Values;

enum RolesEnum: string
{
    use Values;

    case ADMIN = 'admin';
case VOLUNTEER = 'volunteer';
case STORE = 'store';
case TRANSLATOR = 'translator';
case FRIEND_CARD = 'friend card';
}
