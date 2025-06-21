<?php

namespace App\Enums\Adoption;

use ArchTech\Enums\Values;

enum StatusEnum: string
{
    use Values;

    case OPEN = 'open';
    case PENDING = 'pending';
    case CLOSED = 'closed';
    case ARCHIVED = 'archived';
}
