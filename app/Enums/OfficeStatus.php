<?php

namespace App\Enums;

enum OfficeStatus: string
{
    case Active = 'active';
    case Suspended = 'suspended';
    case Archived = 'archived';
}
