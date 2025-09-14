<?php

namespace App\Enums;

enum ReqType: string
{
    case ActiveArmor = 'active_armor';
    case ActiveWeapon = 'active_weapon';
    case Bank = 'bank';
    case BoatProfits = 'boat_profits';
    case Cash = 'cash';
    case DrugProfits = 'drug_profits';
    case FactoryProfits = 'factory_profits';
    case GangMember = 'gang_member';
    case GangRobbery = 'gang_robbery';
    case HookerCount = 'hooker_count';
    case HookerProfits = 'hooker_profits';
    case HookerTypeCount = 'hooker_type_count';
    case KillCount = 'kill_count';
    case Level = 'level';
    case NationalityKillCount = 'nationality_kill_count';
    case Power = 'power';
    case Respect = 'respect';
    case RobberyTarget = 'robbery_target';
    case SingleRobbery = 'single_robbery';
    case TrainingCount = 'training_count';
}
