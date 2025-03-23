<?php

namespace App\Enums\Store;

use ArchTech\Enums\Values;

enum VouchersEnum: string
{
    use Values;

    case USED = 'used';
    case UNUSED = 'unused';
}
