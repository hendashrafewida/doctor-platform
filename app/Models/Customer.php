<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Customer extends Model
{
    protected $fillable = [
        'organization_id',
        'name',
        'city',
        'address',
        'mobile',
        'specialization_id',
        'status',
        'subscription_start_date',
        'subscription_end_date',
    ];

    protected function casts(): array
    {
        return [
            'subscription_start_date' => 'date',
            'subscription_end_date' => 'date',
        ];
    }

    protected static function booted(): void
    {
        static::creating(function (self $customer): void {
            if (empty($customer->subscription_start_date)) {
                $customer->subscription_start_date = now()->toDateString();
            }

            if (empty($customer->subscription_end_date)) {
                $customer->subscription_end_date = $customer->calculateDefaultEndDate(
                    Carbon::parse($customer->subscription_start_date ?? now())
                );
            }
        });

        static::saving(function (self $customer): void {
            if (empty($customer->subscription_start_date) && ! empty($customer->created_at)) {
                $customer->subscription_start_date = $customer->created_at->toDateString();
            }

            if (empty($customer->subscription_end_date) && ! empty($customer->subscription_start_date)) {
                $customer->subscription_end_date = Carbon::parse($customer->subscription_start_date)->addMonth()->toDateString();
            }
        });
    }

    public function renewSubscription(?int $months = 1): void
    {
        $newStart = $this->subscription_end_date
            ? Carbon::parse($this->subscription_end_date)->addDay()->toDateString()
            : now()->toDateString();

        $months = max(1, (int) $months);

        $this->subscription_start_date = $newStart;
        $this->subscription_end_date = Carbon::parse($newStart)->addMonths($months)->toDateString();
        $this->save();
    }

    public function calculateDefaultEndDate(?Carbon $startDate = null): string
    {
        $base = $startDate ?? Carbon::parse($this->subscription_start_date ?? now());

        return $base->copy()->addMonth()->toDateString();
    }

    public function getRemainingDaysAttribute(): ?int
    {
        if ($this->subscription_end_date === null) {
            return null;
        }

        $today = Carbon::now()->startOfDay();
        $endDate = Carbon::parse($this->subscription_end_date)->startOfDay();

        return $today->diffInDays($endDate, false);
    }

    public function organization(): BelongsTo
    {
        return $this->belongsTo(Organization::class);
    }

    public function specialization(): BelongsTo
    {
        return $this->belongsTo(Specialization::class);
    }
}
