<?php

namespace App\Models;

use App\Interfaces\StackableItem;
use App\Interfaces\UniqueItem;
use Illuminate\Database\Eloquent\Model;

class UserFactory extends Model implements UniqueItem
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

    public function getPrice(): int
    {
        return (int) $this->factory->price;
    }

    public function removeFromUser(): void
    {
        $this->delete();
    }

    public function levelUp(int $cost)
    {
        $this->increment('level', 1);
        $this->increment('investment', $cost);
    }
}
