<?php

namespace App\Services;

use App\Enums\ReqType;
use App\Models\Career;
use App\Models\CareerLevel;
use App\Models\CareerLevelParam;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class CareerService
{
    public function getRequirements(Career $career, int $level)
    {
        $level = CareerLevel::where('career_id', $career->id)->where('level', $level)->first();

        return CareerLevelParam::where('career_level_id', $level->id)
            ->whereHas('game_param', function ($query) {
                $query->where('type', 'requirement');
            })
            ->get()
            ->map(fn($clp) => [
                'id' => $clp->game_param->id,
                'name' => $clp->game_param->name,
                'value' => $clp->value,
            ]);
    }

    public function getRewards(Career $career, int $level)
    {
        $level = CareerLevel::where('career_id', $career->id)->where('level', $level)->first();

        return CareerLevelParam::with(['game_param.related'])
            ->where('career_level_id', $level->id)
            ->whereHas('game_param', function ($query) {
                $query->where('type', 'reward');
            })
            ->get()
            ->map(fn($clp) => [
                'id' => $clp->game_param->id,
                'name' => $clp->game_param->name,
                'value' => $clp->value,
                'related' => $clp->game_param->related, // polymorphic model or null
            ]);
    }

    public function evaluateRequirementProgress(User $user, object $req): array
    {
        $current   = 0;
        $total     = $req->value;
        $progress  = 0;
        $completed = false;

        // Direct user props
        if (in_array($req->type, [
            ReqType::Cash,
            ReqType::Bank,
            ReqType::Level,
            ReqType::BoatProfits,
            ReqType::FactoryProfits,
            ReqType::HookerProfits,
            ReqType::Respect,
        ], true)) {
            $current = $user->{$req->type->value} ?? 0;
        }

        // Power
        if ($req->type === ReqType::Power) {
            $current = max($user->single_robbery_power, $user->gang_robbery_power, $user->assault_power);
        }

        // Active equipment
        if (in_array($req->type, [ReqType::ActiveWeapon, ReqType::ActiveArmor], true)) {
            $column  = $req->type === ReqType::ActiveArmor ? 'armor_id' : 'weapon_id';
            $current = $user->{$column} === $req->target_id ? 1 : 0;
        }

        // Gang member
        if ($req->type === ReqType::GangMember) {
            $current = $user->gang_member ? 1 : 0;
        }

        // Hooker counts
        if ($req->type === ReqType::HookerCount) {
            $current = DB::scalar('select req_get_hooker_count()'); // stored function
        }

        if ($req->type === ReqType::HookerTypeCount) {
            $current = DB::scalar('select req_get_hooker_count(?)', [$req->target_id]);
        }

        // Robbery counts
        if (in_array($req->type, [ReqType::SingleRobbery, ReqType::GangRobbery], true)) {
            $type    = $req->type === ReqType::SingleRobbery ? 'solo' : 'gang';
            $current = DB::scalar('select req_get_robbery_type_count(?)', [$type]);
        }

        if ($req->type === ReqType::RobberyTarget) {
            $current = DB::scalar('select req_get_robbery_target_count(?)', [$req->target_id]);
        }

        // Kill count
        if ($req->type === ReqType::KillCount) {
            $current = DB::scalar('select req_get_kill_count()');
        }

        // Kills by nationality
        if ($req->type === ReqType::NationalityKillCount) {
            $current = DB::scalar('select req_get_nationality_kill_count(?)', [$req->target_id]);
        }

        // Factories count
        if ($req->type->value === 'factory_count') {
            $current = DB::table('user_factories')
                ->where('user_id', $user->id)
                ->count();
        }

        // Drug count
        if ($req->type->value === 'drug_count') {
            $current = DB::scalar('select req_get_drug_count()');
        }

        // Drug type count
        if ($req->type->value === 'drug_type_count') {
            $current = DB::scalar('select req_get_drug_count(?)', [$req->target_id]);
        }

        $completed = $current >= $total;
        $progress  = min(100, (int) round(($current / max(1, $total)) * 100));

        return [
            ...(array) $req,
            'current'   => $current,
            'total'     => $total,
            'progress'  => $progress,
            'completed' => $completed,
        ];
    }
}
