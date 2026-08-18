<?php

namespace App\Models;

use App\Interfaces\Sellable;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Factory;
use App\Models\LabProduction;

class UserFactory extends Model implements Sellable
{
    protected $fillable = [
        'user_id',
        'factory_id',
        'level',
        'investment',
        'stash'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function factory()
    {
        return $this->belongsTo(Factory::class);
    }

    public function productions()
    {
        return $this->hasMany(LabProduction::class)->with('drug');
    }

    public function getPrice(): int
    {
        return (int) $this->investment;
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
            throw new \RuntimeException("Você não possui esta fábrica: {$this->getName()}.");
        }

        if ($this->factory->is_lab && $this->productions()->exists()) {
            throw new \RuntimeException("Não é possível vender o laboratório enquanto houver produções em andamento ou prontas para coleta.");
        }
    }

    public function getUpgradeCost(): int
    {
        return (int) ($this->factory->price * 0.5 * $this->level);
    }

    public function levelUp(int $cost): void
    {
        $this->increment('level');
        $this->increment('investment', $cost);
    }
}
