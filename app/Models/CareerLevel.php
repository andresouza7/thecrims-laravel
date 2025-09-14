<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CareerLevel extends Model
{
    use HasFactory;

    protected $fillable = [
        'career_id',
        'name',
        'level'
    ];

    public function career()
    {
        return $this->belongsTo(Career::class);
    }

    public function params()
    {
        return $this->hasMany(CareerLevelParam::class);
    }

    public function getRequirements()
    {
        return CareerLevelParam::where('career_level_id', $this->id)
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

    public function getRewards()
    {
        return CareerLevelParam::with(['game_param.related'])
            ->where('career_level_id', $this->id)
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
}
