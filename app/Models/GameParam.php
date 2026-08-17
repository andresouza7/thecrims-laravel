<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GameParam extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'type',
        'target_id',
        'target_type',
    ];

    public function target()
    {
        return $this->morphTo();
    }

    public function scopeRequirements($query)
    {
        return $query->where('type', 'requirement');
    }

    public function scopeRewards($query)
    {
        return $query->where('type', 'reward');
    }
}
