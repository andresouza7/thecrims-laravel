<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class LabProduction extends Model
{
    protected $fillable = [
        'user_factory_id',
        'drug_id',
        'amount',
        'ends_at'
    ];

    protected $casts = [
        'ends_at'    => 'datetime',
        'created_at' => 'datetime',
    ];

    protected $appends = [
        'progress',
        'remaining_time',
    ];

    public function lab()
    {
        return $this->belongsTo(UserFactory::class)->with('factory');
    }

    public function drug()
    {
        return $this->belongsTo(Drug::class);
    }

    public function getProgressAttribute(): int
    {
        $start = $this->created_at;
        $end   = $this->ends_at;

        if (! $start instanceof Carbon || ! $end instanceof Carbon) {
            return 0;
        }

        $startTs = $start->getTimestamp();
        $endTs   = $end->getTimestamp();
        $nowTs   = Carbon::now()->getTimestamp();

        $total = $endTs - $startTs;
        if ($total <= 0) {
            return 100;
        }

        $elapsed = $nowTs - $startTs;

        if ($elapsed <= 0) {
            return 0;
        }

        if ($elapsed >= $total) {
            return 100;
        }

        $pct = (int) round(($elapsed / $total) * 100);

        return max(0, min(100, $pct));
    }

    public function getRemainingTimeAttribute(): int
    {
        if (! $this->ends_at instanceof Carbon) {
            return 0;
        }

        $diff = $this->ends_at->getTimestamp() - Carbon::now()->getTimestamp();

        return max(0, $diff);
    }
}
