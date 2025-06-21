<?php

namespace App\Enums;

use ArchTech\Enums\Values;

enum FormsEnum: string
{
    use Values;

    case VOLUNTEER = 'volunteer';
    case CONTACT = 'contact';
    case APPLY = 'apply';
    case TRAINING = 'training';
    case GODFATHER = 'godfather';
    case PETSITTING = 'petsitting';
}
