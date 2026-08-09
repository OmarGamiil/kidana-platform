<?php

namespace App\Enums;

enum ReadinessTrackType: string
{
    case Camps = 'camps';
    case Housing = 'housing';
    case Catering = 'catering';
    case Transport = 'transport';
}
