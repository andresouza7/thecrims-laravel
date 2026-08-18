<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Boat extends Model
{
    protected $fillable = ['day', 'drug_id', 'is_gone'];

    protected $casts = [
        'is_gone' => 'boolean',
    ];

    public function drug()
    {
        return $this->belongsTo(Drug::class);
    }
}
