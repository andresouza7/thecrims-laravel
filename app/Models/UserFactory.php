<?php

namespace App\Models;

use App\Interfaces\Sellable;
use Illuminate\Database\Eloquent\Model;

class UserFactory extends Model implements Sellable
{
    protected $fillable = [
        'user_id', 'factory_id', 'level', 'investment', 'stash'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function factory()
    {
        return $this->belongsTo(Factory::class);
    }

    public function getPrice(): int
    {
        return (int) $this->factory->price;
    }

    public function getName(): string
    {
        return $this->factory->name;
    }

    public function removeFromUser(User $user, int $quantity = 1): void
    {
        $this->delete();
    }

    public function validateInventory(User $user, int $quantity = 1): void
    {
        if ($this->user_id !== $user->id) {
            throw new \RuntimeException("You don’t own this {$this->getName()}.");
        }
    }

    public function levelUp(int $cost): void
    {
        $this->increment('level');
        $this->increment('investment', $cost);
    }
}

