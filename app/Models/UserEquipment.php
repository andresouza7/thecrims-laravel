<?php

namespace App\Models;

use App\Interfaces\Sellable;
use Illuminate\Database\Eloquent\Model;

class UserEquipment extends Model implements Sellable
{
     protected $fillable = [
        'user_id', 'equipment_id'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function equipment()
    {
        return $this->belongsTo(Equipment::class);
    }

    public function getPrice(): int
    {
        return (int) $this->equipment->price;
    }

    public function getName(): string
    {
        return $this->equipment->name;
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
}
