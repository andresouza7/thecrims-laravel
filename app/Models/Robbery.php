<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Robbery extends Model
{
    protected $fillable = [
        'description',
        'required_power',
        'required_stamina',
        'type',
        'cash',
        'drugs',
        'components',
    ];

    protected $casts = [
        'drugs' => 'array',
        'components' => 'array',
    ];
}
