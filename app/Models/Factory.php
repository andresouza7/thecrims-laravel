<?php

namespace App\Models;

use App\Interfaces\Buyable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Factory extends Model implements Buyable
{
    use HasFactory;

    public function drug() {
        return $this->belongsTo(Drug::class);
    }

    public function getPrice(): int
    {
        return (int) $this->price;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'user_factories')
            ->withPivot(['level', 'investment', 'stash'])
            ->withTimestamps();
    }

    public function addToUser(User $user, int $quantity = 1): void
    {
        // Each factory is unique → just attach once
        $user->factories()->attach($this->id, [
            'level' => 1,
            'investment' => $this->price,
        ]);
    }
}

