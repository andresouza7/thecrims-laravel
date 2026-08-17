<?php

namespace App\Services;

use App\Enums\ReqType;
use App\Models\Career;
use App\Models\CareerLevel;
use App\Models\CareerLevelParam;
use App\Models\User;
use App\Services\GameProgress\RequirementEvaluatorFactory;
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

    public function evaluateRequirementsProgress(User $user, object $req): array
    {
        $current   = 0;
        $total     = $req->value;
        $progress  = 0;
        $completed = false;

        $evaluator = RequirementEvaluatorFactory::make($req->name);
        $current   = $evaluator->evaluate($user, $req);

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

    public function evaluateRequirementProgress(User $user, object $req): array
    {
        $current   = 0;
        $total     = $req->value;
        $progress  = 0;
        $completed = false;

        

        // Direct user props
        if (in_array($req->game_param->name, [
            ReqType::Cash,
            ReqType::Bank,
            ReqType::Level,
            ReqType::BoatProfits,
            ReqType::FactoryProfits,
            ReqType::HookerProfits,
            ReqType::Respect,
        ], true)) {
            dd($completed);
            $current = $user->{$req->game_param->name} ?? 0;
        }

        // if ($req->name === ReqType::Power) {
        //     $current = max($user->single_robbery_power, $user->gang_robbery_power, $user->assault_power);
        // }

        // if (in_array($req->name, [ReqType::ActiveWeapon, ReqType::ActiveArmor], true)) {
        //     $column  = $req->name === ReqType::ActiveArmor ? 'armor_id' : 'weapon_id';
        //     $current = $user->{$column} === $req->target_id ? 1 : 0;
        // }

        // if ($req->name === ReqType::GangMember) {
        //     $current = $user->gang_member ? 1 : 0;
        // }

        // if ($req->name === ReqType::HookerCount) {
        //     $current = $user->hookers()->sum('amount');
        // }

        // if ($req->name === ReqType::HookerTypeCount) {
        //     $relatedId = $req->related->id;
        //     $hooker = $user->hookers()->where('related_id', $relatedId)->first();

        //     $current = $hooker ? $hooker->amount : 0;
        // }

        // if (in_array($req->name, [ReqType::SingleRobbery, ReqType::GangRobbery], true)) {
        //     $type    = $req->name === ReqType::SingleRobbery ? 'solo' : 'gang';
        //     $current = DB::scalar('select req_get_robbery_type_count(?)', [$type]);
        // }

        // if ($req->name === ReqType::RobberyTarget) {
        //     $current = DB::scalar('select req_get_robbery_target_count(?)', [$req->target_id]);
        // }

        // if ($req->name === ReqType::KillCount) {
        //     $current = DB::scalar('select req_get_kill_count()');
        // }

        // if ($req->name === ReqType::NationalityKillCount) {
        //     $current = DB::scalar('select req_get_nationality_kill_count(?)', [$req->target_id]);
        // }

        // if ($req->value === 'factory_count') {
        //     $current = DB::table('user_factories')
        //         ->where('user_id', $user->id)
        //         ->count();
        // }

        // if ($req->value === 'drug_count') {
        //     $current = DB::scalar('select req_get_drug_count()');
        // }

        // if ($req->value === 'drug_type_count') {
        //     $current = DB::scalar('select req_get_drug_count(?)', [$req->target_id]);
        // }

        $completed = $current >= $total;
        $progress  = min(100, (int) round(($current / max(1, $total)) * 100));

        return [
            'current'   => $current,
            'total'     => $total,
            'progress'  => $progress,
            'completed' => $completed,
        ];
    }
}
