<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TaskParam extends Model
{
    use HasFactory;

    protected $fillable = [
        'task_id',
        'game_param_id',
        'value'
    ];

    public function task()
    {
        return $this->belongsTo(Task::class);
    }

    public function game_param()
    {
        return $this->belongsTo(GameParam::class);
    }

    public function target()
    {
        return $this->game_param?->target();
    }
}
