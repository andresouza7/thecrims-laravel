<?php

namespace App\Models;

use App\Interfaces\Buyable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Factory extends Model implements Buyable
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

    public function addToUser(Model $user, int $level = 1, int $investment = 0): void
    {
        // Since each factory is unique, just attach if not already owned
        $user->factories()->syncWithoutDetaching([
            $this->id => [
                'level' => $level,
                'investment' => $investment
            ],
        ]);
    }

    public function removeFromUser(Model $user, int $quantity = 1): void
    {
        // Simply detach the factory from the user
        $user->factories()->detach($this->id);
    }

    public function upgrade(Model $user, int $cost): void
    {
        $user->factories()->syncWithoutDetaching([
            $this->id => [
                'level' => DB::raw("level + 1"),
                'investment' => DB::raw("COALESCE(investment,0)+$cost")
            ],
        ]);
    }
}
