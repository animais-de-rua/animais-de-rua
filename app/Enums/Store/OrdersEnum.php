<?php

namespace App\Enums\Store;

use ArchTech\Enums\Values;

enum OrdersEnum: string
{
    use Values;

    case WAITING = 'waiting';
    case IN_PROGRESS = 'in_progress';
    case SHIPPED = 'shipped';
}
