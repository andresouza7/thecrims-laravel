<?php

namespace App\Models;

use App\Interfaces\Buyable;
use Illuminate\Database\Eloquent\Model;

class UserFactory extends Model implements Buyable
{
    protected $fillable =[
        'user_id',
        'factory_id',
        'level',
        'investment',
        'stash'
    ];

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function factory() {
        return $this->belongsTo(Factory::class);
    }

    public function getPrice(): int {
        return $this->factory->price;
    }

    public function getAmountForUser(Model $user): int {
        return 1;
    }

    public function addToUser(Model $user, int $quantity): void
    {
        
    }

    public function removeFromUser(Model $user, int $quantity): void
    {
        $this->delete();
    }
}
