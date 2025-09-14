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
        'related_id',
        'related_type',
    ];

    public function related()
    {
        return $this->morphTo();
    }
}
