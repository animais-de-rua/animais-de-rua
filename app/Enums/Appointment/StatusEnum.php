<?php

namespace App\Enums\Appointment;

use ArchTech\Enums\Values;

enum StatusEnum: string
{
    use Values;

    case APPROVING = 'approving';
    case APPROVED_OPTION_1 = 'approved_option_1';
    case APPROVED_OPTION_2 = 'approved_option_2';
}
