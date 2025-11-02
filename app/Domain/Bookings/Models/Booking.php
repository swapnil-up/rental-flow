<?php

namespace Domain\Bookings\Models;

use Domain\Properties\Models\Property;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Database\Factories\BookingFactory;

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

    public function property(): BelongsTo
    {
        return $this->belongsTo(Property::class);
    }

    protected static function newFactory()
    {
        return BookingFactory::new();
    }
}