<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Boat extends Model
{
    public function drug()
    {
        return $this->belongsTo(Drug::class);
    }
}
