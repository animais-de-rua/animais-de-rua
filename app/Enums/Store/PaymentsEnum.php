<?php

namespace App\Enums\Store;

use ArchTech\Enums\Values;

enum PaymentsEnum: string
{
    use Values;

    case BANK_TRANSFER = 'bank_transfer';
    case PAYPAL = 'paypal';
    case MBWAY = 'mbway';
    case CREDIT_CARD = 'credit_card';
}
