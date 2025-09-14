<?php

namespace App\Enums;

enum CareerParamType: string
{
    case REQUIREMENT = 'requirement';
    case DRAWBACK = 'drawback';
    case BENEFIT = 'benefit';
    case REWARD = 'reward';
}
