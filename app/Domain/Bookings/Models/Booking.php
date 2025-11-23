<?php

namespace Domain\Bookings\Models;

use Domain\Bookings\States\BookingState;
use Domain\Bookings\States\PendingBookingState;
use Domain\Bookings\States\ConfirmedBookingState;
use Domain\Bookings\States\ActiveBookingState;
use Domain\Bookings\States\CompletedBookingState;
use Domain\Bookings\States\CancelledBookingState;
use Domain\Properties\Models\Property;
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
        'check_in',
        'check_out',
        'status',
    ];

    protected $casts = [
        'check_in' => 'date',
        'check_out' => 'date',
    ];

    /**
     * Get the booking state object
     */
    public function state(): Attribute
    {
        return Attribute::make(
            get: function () {
                $stateClass = $this->getStateClass();
                return new $stateClass($this);
            }
        );
    }

    /**
     * Get the state class based on status
     */
    protected function getStateClass(): string
    {
        return match($this->status) {
            'pending' => PendingBookingState::class,
            'confirmed' => ConfirmedBookingState::class,
            'active' => ActiveBookingState::class,
            'completed' => CompletedBookingState::class,
            'cancelled' => CancelledBookingState::class,
            default => PendingBookingState::class,
        };
    }

    public function property(): BelongsTo
    {
        return $this->belongsTo(Property::class);
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