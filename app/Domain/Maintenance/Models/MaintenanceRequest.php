<?php

namespace Domain\Maintenance\Models;

use Database\Factories\MaintenanceRequestFactory;
use Domain\Bookings\Models\Booking;
use Domain\Maintenance\States\MaintenanceRequestStatus;
use Domain\Properties\Models\Property;
use Domain\Tenants\Models\Tenant;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MaintenanceRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'property_id',
        'tenant_id',
        'title',
        'description',
        'priority',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'status' => MaintenanceRequestStatus::class,
        ];
    }

    public function property(): BelongsTo
    {
        return $this->belongsTo(Property::class);
    }

    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Tenant::class);
    }

    protected static function newFactory()
    {
        return MaintenanceRequestFactory::new();
    }
}
