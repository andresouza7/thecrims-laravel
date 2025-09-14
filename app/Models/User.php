<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Enums\VitalType;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

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
        'in_hospital',
        'jail_release_cost',
        'hospital_release_cost',
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

    public function equipment()
    {
        return $this->belongsToMany(Equipment::class, 'user_equipment')
            ->withPivot(['id', 'active'])
            ->withTimestamps();
    }

    public function armor()
    {
        return $this->belongsTo(Equipment::class, 'armor_id');
    }

    public function weapon()
    {
        return $this->belongsTo(Equipment::class, 'weapon_id');
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

    public function setVitals(VitalType $type, int $amount): void
    {
        $this->{$type->value} = $amount;
        $this->save();
    }

    public function adjustVitals(VitalType $type, int $amount): void
    {
        $currentValue = (int) $this->getAttribute($type->value);
        $newValue = $currentValue + $amount;

        $max = match ($type) {
            VitalType::HEALTH => (int) $this->getAttribute('max_health'),
            VitalType::STAMINA, VitalType::ADDICTION => 100,
        };

        if ($newValue < 0 || $newValue > $max) {
            throw new \RuntimeException('invalid amount!');
        }

        $this->increment($type->value, $amount);
    }

    public function adjustStats(int $amount)
    {
        $this->increment('strength', $amount);
        $this->increment('intelligence', $amount);
        $this->increment('charisma', $amount);
        $this->increment('tolerance', $amount);
    }

    protected function getActiveArmor(): int
    {
        return $this->armor ? $this->armor->base_damage : 0;
    }

    protected function getActiveWeapon(): int
    {
        return $this->weapon ? $this->weapon->base_damage : 0;
    }
   
    public function getInJailAttribute(): bool
    {
        return $this->jail_end_time ? Carbon::now()->lt($this->jail_end_time) : false;
    }

    public function getInHospitalAttribute(): bool
    {
        return $this->hospital_end_time ? Carbon::now()->lt($this->hospital_end_time) : false;
    }

    public function getJailReleaseCostAttribute(): int
    {
        return $this->assault_power * 1000;
    }

    public function getHospitalReleaseCostAttribute(): int
    {
        return $this->respect * 1000;
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
            ) + $this->getActiveArmor() + $this->getActiveWeapon()
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
            ) + $this->getActiveArmor() + $this->getActiveWeapon()
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
                + $this->getActiveArmor() + $this->getActiveWeapon()
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
