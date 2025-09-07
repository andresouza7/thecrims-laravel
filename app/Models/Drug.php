<?php

namespace App\Models;

use App\Interfaces\Buyable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Drug extends Model implements Buyable
{
    use HasFactory;

    public function getPrice(): int
    {
        return (int) $this->price;
    }

    public function users()
    {
        return $this->belongsToMany(User::class, 'user_drugs')
            ->withPivot(['amount'])
            ->withTimestamps();
    }

    public function addToUser(Model $user, int $quantity): void
    {
        // Adds or increments amount automatically
        $user->drugs()->syncWithoutDetaching([
            $user->id => ['amount' => DB::raw("COALESCE(amount,0)+$quantity")],
        ]);
    }

    public function removeFromUser(Model $user, int $quantity): void
    {
        // Decrement pivot amount and remove if zero
        $pivot = $user->drugs()->where('user_id', $user->id)->first();

        if (!$pivot) return;

        $newAmount = $pivot->pivot->amount - $quantity;

        $newAmount > 0
            ? $user->drugs()->updateExistingPivot($user->id, ['amount' => $newAmount])
            : $user->drugs()->detach($user->id);
    }
}
