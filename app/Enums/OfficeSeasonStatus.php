<?php

namespace App\Enums;

enum OfficeSeasonStatus: string
{
    case PendingActivation = 'pending_activation';
    case Active = 'active';
    case Suspended = 'suspended';
    case Completed = 'completed';
}
