<?php

namespace App\Enums\Store;

use ArchTech\Enums\Values;

enum SuppliersEnum: string
{
    use Values;

    case WAITING_PAYMENT = 'waiting_payment';
    case PAID_OUT = 'paid_out';
}
