<?php

namespace App\Models;

use App\Interfaces\Buyable;
use App\Interfaces\Sellable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Equipment extends Model implements Buyable
{
    use HasFactory;

    protected $table = 'equipment';

    public function drug()
    {
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
        return $this->belongsToMany(User::class, 'user_equipment')
            ->withPivot(['id'])
            ->withTimestamps();
    }

    public function addToUser(User $user, int $quantity = 1): void
    {
        $this->users()->attach($user->id);
    }

    public function removeFromUser(User $user, int $quantity = 1, int $pivotId): void
    {
        $row = $this->users()->where('id', 'pivotId')->first();
        $row->delete();
    }
}
