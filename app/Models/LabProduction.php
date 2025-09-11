<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LabProduction extends Model
{
    public function lab() {
        return $this->belongsTo(Factory::class);
    }
}
