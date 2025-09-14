<?php

namespace App\Enums;

enum VitalType: string
{
    case HEALTH = 'health';
    case STAMINA = 'stamina';
    case ADDICTION = 'addiction';
}
