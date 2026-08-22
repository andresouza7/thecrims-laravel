<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    use HasFactory;

    protected $fillable = [
        'task_category_id',
        'name',
        'description',
        'order',
    ];

    public function category()
    {
        return $this->belongsTo(TaskCategory::class, 'task_category_id');
    }

    public function params()
    {
        return $this->hasMany(TaskParam::class);
    }

    public function getRequirements()
    {
        return TaskParam::with(['game_param.target'])
            ->where('task_id', $this->id)
            ->whereHas('game_param', function ($query) {
                $query->where('type', 'requirement');
            })
            ->get();
    }

    public function getRewards()
    {
        return TaskParam::with(['game_param.target'])
            ->where('task_id', $this->id)
            ->whereHas('game_param', function ($query) {
                $query->where('type', 'reward');
            })
            ->get();
    }
}
