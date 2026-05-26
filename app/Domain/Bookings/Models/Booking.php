<?php

namespace Domain\Bookings\Models;

use Domain\Bookings\States\BookingState;
use Domain\Bookings\States\BookingStatus;
use Domain\Properties\Models\Property;
use Domain\Tenants\Models\Tenant;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Database\Factories\BookingFactory;
use Domain\Bookings\Collections\BookingCollection;
use Domain\Bookings\QueryBuilders\BookingQueryBuilder;
use Illuminate\Database\Eloquent\Casts\Attribute;

class Booking extends Model
{
    use HasFactory;

    protected $fillable = [
        'property_id',
        'tenant_id',
        'check_in',
        'check_out',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'check_in' => 'date',
            'check_out' => 'date',
            'status' => BookingStatus::class,
        ];
    }

    public function state(): Attribute
    {
        return Attribute::make(
            get: fn(): BookingState => $this->status->resolveState($this),
        );
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
        return BookingFactory::new();
    }

    public function newEloquentBuilder($query): BookingQueryBuilder
    {
        return new BookingQueryBuilder($query);
    }

    public function newCollection(array $models = []): BookingCollection
    {
        return new BookingCollection($models);
    }
}
