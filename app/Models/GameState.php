<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GameState extends Model
{
    protected $table = 'game_state';
    
    protected $fillable = ['current_day', 'total_days', 'start_time'];


}
