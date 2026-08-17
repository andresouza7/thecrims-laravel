<?php

namespace App\Models;

use App\Interfaces\Sellable;
use Illuminate\Database\Eloquent\Model;

class UserEquipment extends Model implements Sellable
{
    protected $table = 'user_equipment';

    protected $fillable = [
        'user_id', 'equipment_id', 'active'
    ];

    protected $casts = [
        'active' => 'boolean',
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
        return (int) floor(($this->equipment?->price ?? 0) / 2);
    }

    public function getName(): string
    {
        return $this->equipment?->name ?? 'Equipamento';
    }

    public function removeFromUser(User $user, int $quantity = 1): void
    {
        if ($user->armor_id === $this->equipment_id) {
            $user->armor_id = null;
            $user->save();
        }
        if ($user->weapon_id === $this->equipment_id) {
            $user->weapon_id = null;
            $user->save();
        }
        $this->delete();
    }

    public function validateInventory(User $user, int $quantity = 1): void
    {
        if ($this->user_id !== $user->id) {
            throw new \RuntimeException("Você não possui este equipamento.");
        }
    }
}
