<?php

namespace App\Models;

use App\Interfaces\UniqueItem;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Factory extends Model
{
    use HasFactory;

    public function getPrice(): int
    {
        return (int) $this->price;
    }

    public function users()
    {
        return $this->belongsToMany(User::class, 'user_factories')
            ->withPivot(['level', 'investment', 'stash'])
            ->withTimestamps();
    }

    public function addToUser(Model $user): void
    {
        // Since each factory is unique, just attach if not already owned
        $user->factories()->attach(
            $this->id,
            [
                'level' => 1,
                'investment' => $this->price
            ],
        );
    }
}
