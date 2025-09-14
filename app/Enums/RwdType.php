<?php

namespace App\Enums;

enum RwdType: string
{
    case Cash = 'cash';
    case Component = 'component';
    case Drug = 'drug';
    case Drugs = 'drugs';
    case Hooker = 'hooker';
    case Stats = 'stats';
    case Stamina = 'stamina';
    case Tickets = 'tickets';
    case PrisonEscapeItems = 'prison_escape_items';
}
