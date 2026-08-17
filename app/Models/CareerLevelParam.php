<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CareerLevelParam extends Model
{
    use HasFactory;

    protected $fillable = [
        'career_level_id',
        'game_param_id',
        'value'
    ];

    public function career_level()
    {
        return $this->belongsTo(CareerLevel::class);
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
