<?php

namespace App\Models;

use App\Interfaces\StackableItem;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Component extends Model implements StackableItem
{
    use HasFactory;

    public function getPrice(): int
    {
        return (int) $this->price;
    }

    public function getAmountForUser(Model $user): int
    {
        $row = $this->users()->where('user_id', $user->id)->first();
        return $row ? $row->pivot->amount : 0; 
    }

    public function users()
    {
        return $this->belongsToMany(User::class, 'user_components')
            ->withPivot(['amount'])
            ->withTimestamps();
    }

    public function addToUser(Model $user, int $quantity): void
    {
        // Adds or increments amount automatically
        $user->components()->syncWithoutDetaching([
            $user->id => ['amount' => DB::raw("COALESCE(amount,0)+$quantity")],
        ]);
    }

    public function removeFromUser(Model $user, int $quantity): void
    {
        // Decrement pivot amount and remove if zero
        $row = $user->components()->where('user_id', $user->id)->first();

        if (!$row) return;

        $newAmount = $row->pivot->amount - $quantity;

        $newAmount > 0
            ? $user->components()->updateExistingPivot($user->id, ['amount' => $newAmount])
            : $user->components()->detach($user->id);
    }
}
