<?php

namespace App\Enums\General;

use ArchTech\Enums\Values;

enum FriendCardModalitiesEnum: string
{
    use Values;

    case MONTHLY = 'monthly';
    case YEARLY = 'yearly';
}
