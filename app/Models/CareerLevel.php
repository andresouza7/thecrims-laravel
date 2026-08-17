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
        return CareerLevelParam::with(['game_param.target'])
            ->where('career_level_id', $this->id)
            ->whereHas('game_param', function ($query) {
                $query->where('type', 'requirement');
            })
            ->get();
    }

    public function getRewards()
    {
        return CareerLevelParam::with(['game_param.target'])
            ->where('career_level_id', $this->id)
            ->whereHas('game_param', function ($query) {
                $query->where('type', 'reward');
            })
            ->get();
    }
}
