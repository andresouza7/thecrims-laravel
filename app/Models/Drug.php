<?php

namespace App\Models;

use App\Interfaces\Buyable;
use App\Interfaces\Sellable;
use App\Interfaces\Stackable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Facades\DB;

class Drug extends Model implements Buyable, Sellable, Stackable
{
    use HasFactory;

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
        return $this->belongsToMany(User::class, 'user_drugs')
            ->withPivot(['amount', 'total_sold'])
            ->withTimestamps();
    }

    public function getAmountForUser(User $user): int
    {
        $row = $this->users()->where('user_id', $user->id)->first();
        return $row ? $row->pivot->amount : 0;
    }

    public function addToUser(User $user, int $quantity = 1): void
    {
        $this->users()->syncWithoutDetaching([
            $user->id => ['amount' => DB::raw("COALESCE(amount,0)+$quantity")],
        ]);
    }

    public function removeFromUser(User $user, int $quantity = 1): void
    {
        $row = $this->users()->where('user_id', $user->id)->first();
        if (!$row) return;

        $newAmount = max(0, $row->pivot->amount - $quantity);
        $newTotalSold = ($row->pivot->total_sold ?? 0) + $quantity;

        $this->users()->updateExistingPivot($user->id, [
            'amount' => $newAmount,
            'total_sold' => $newTotalSold,
        ]);
    }

    public function validateInventory(User $user, int $quantity = 1): void
    {
        if ($this->getAmountForUser($user) < $quantity) {
            throw new \RuntimeException("Not enough of {$this->name} to sell.");
        }
    }
}
