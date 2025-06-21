<?php

namespace App\Enums\Process;

use ArchTech\Enums\Values;

enum StatusEnum: string
{
    use Values;

    case APPROVING = 'approving';
    case WAITING_GODFATHER = 'waiting_godfather';
    case WAITING_CAPTURE = 'waiting_capture';
    case OPEN = 'open';
    case CLOSED = 'closed';
    case ARCHIVED = 'archived';
}
