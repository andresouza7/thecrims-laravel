<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    protected $appends = [
        'in_jail',
        'robbery_skill',
        'assault_skill',
        'single_robbery_power',
        'gang_robbery_power',
        'assault_power',
        'respect',
        'hooker_income'
    ];

    public function career()
    {
        return $this->belongsTo(Career::class);
    }

    public function components()
    {
        return $this->belongsToMany(Component::class, 'user_components')
            ->withPivot(['amount'])
            ->withTimestamps();
    }

    public function drugs()
    {
        return $this->belongsToMany(Drug::class, 'user_drugs')
            ->withPivot(['amount'])
            ->withTimestamps();
    }

    public function factories()
    {
        return $this->belongsToMany(Factory::class, 'user_factories')
            ->withPivot(['id', 'level', 'investment', 'stash'])
            ->withTimestamps();
    }

    public function hookers()
    {
        return $this->belongsToMany(Hooker::class, 'user_hookers')
            ->withPivot(['amount', 'available_income', 'total_income'])
            ->withTimestamps();
    }

    public function robberies()
    {
        return $this->belongsToMany(Robbery::class)
            ->withPivot(['success_count', 'fail_count'])
            ->withTimestamps();
    }

    public function validateFunds(int $cost): void
    {
        if ($this->cash < $cost) {
            throw new \RuntimeException("Not enough cash.");
        }
    }

    public function adjustCash(int $amount): void
    {
        $accountBlocked = false;

        if ($accountBlocked) throw new \RuntimeException('your account is blocked, sort this out to make transactions');

        $this->increment('cash', $amount);
    }

    public function adjustStat(string $type, int $amount): bool
    {
        $currentValue = (int) $this->getAttribute($type);
        $newValue = $currentValue + $amount;

        // Determine maximum value
        $max = $newValue;
        if ($type === 'health') {
            $max = (int) $this->getAttribute('max_health');
        } elseif (in_array($type, ['stamina', 'addiction'])) {
            $max = 100;
        }

        // Apply bounds
        if ($newValue < 0 || $newValue > $max) {
            return false;
        }

        $this->increment($type, $amount);

        return true;
    }

    public function sendToJail(int $minutes = 30): void
    {
        $this->jail_end_time = Carbon::now()->addMinutes($minutes);
        $this->save();
    }

    /**
     * Release the user from jail immediately.
     */
    public function releaseFromJail(): void
    {
        $this->jail_end_time = null;
        $this->save();
    }

    /**
     * Check if the user is currently in jail.
     */
    public function getInJailAttribute(): bool
    {
        return $this->jail_end_time ? Carbon::now()->lt($this->jail_end_time) : false;
    }

    // Robbery skill
    public function getRobberySkillAttribute(): int
    {
        return 1;
    }

    // Assault skill
    public function getAssaultSkillAttribute(): int
    {
        return 1;
    }

    // Single robbery power
    public function getSingleRobberyPowerAttribute(): int
    {
        return (int) round(
            (
                ($this->intelligence * 0.5 +
                    $this->tolerance * 0.25 +
                    $this->charisma * 0.1 +
                    $this->strength * 0.15) * 0.6 * $this->robbery_skill
            ) + $this->armor + $this->weapon
        );
    }

    // Gang robbery power
    public function getGangRobberyPowerAttribute(): int
    {
        return (int) round(
            (
                ($this->intelligence * 0.25 +
                    $this->tolerance * 0.5 +
                    $this->charisma * 0.1 +
                    $this->strength * 0.15) * 0.6 * $this->robbery_skill
            ) + $this->armor + $this->weapon
        );
    }

    // Assault power
    public function getAssaultPowerAttribute(): int
    {
        $vip_bonus = 1;
        return (int) round(
            (
                ($this->intelligence * 0.05 +
                    $this->tolerance * 0.25 +
                    $this->strength * 0.7) / 2
            ) * $this->assault_skill * $vip_bonus
                + $this->armor + $this->weapon
        );
    }

    // Respect
    public function getRespectAttribute(): int
    {
        return (int) ceil(
            (
                ($this->intelligence + $this->strength + $this->charisma + $this->tolerance) / 8
            ) + ($this->cash / 30000)
        );
    }

    public function getHookerIncomeAttribute(): int
    {
        return $this->hookers->sum(fn($h) => $h->pivot->available_income);
    }
}
