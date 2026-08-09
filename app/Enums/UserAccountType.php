<?php

namespace App\Enums;

enum UserAccountType: string
{
    case HajjOffice = 'hajj_office';
    case ServiceProvider = 'service_provider';
    case KidanaTeam = 'kidana_team';
}
