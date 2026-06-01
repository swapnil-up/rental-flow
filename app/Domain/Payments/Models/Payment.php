<?php

namespace Domain\Payments\Models;

use Database\Factories\PaymentFactory;
use Domain\Bookings\Models\Booking;
use Domain\Payments\States\PaymentStatus;
use Domain\Tenants\Models\Tenant;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'booking_id',
        'tenant_id',
        'amount',
        'type',
        'status',
        'due_date',
        'paid_at',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'status' => PaymentStatus::class,
            'due_date' => 'date',
            'paid_at' => 'date',
        ];
    }

    public function booking(): BelongsTo
    {
        return $this->belongsTo(Booking::class);
    }

    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Tenant::class);
    }

    protected static function newFactory()
    {
        return PaymentFactory::new();
    }
}
