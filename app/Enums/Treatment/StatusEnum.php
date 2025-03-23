<?php

namespace App\Enums\Treatment;

use ArchTech\Enums\Values;

enum StatusEnum: string
{
    use Values;

    case APPROVING = 'approving';
    case APPROVED = 'approved';
}
