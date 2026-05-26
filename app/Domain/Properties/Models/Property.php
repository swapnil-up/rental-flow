<?php

namespace Domain\Properties\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Domain\Bookings\Models\Booking;
use Database\Factories\PropertyFactory;
use Domain\Properties\States\PropertyStatus;
use Domain\Properties\States\PropertyState;
use Illuminate\Database\Eloquent\Casts\Attribute;

class Property extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'type',
        'address',
        'city',
        'state',
        'zip_code',
        'bedrooms',
        'bathrooms',
        'square_feet',
        'monthly_rent',
        'utilities_cost',
        'management_fee',
        'total_monthly_cost',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'monthly_rent' => 'integer',
            'utilities_cost' => 'integer',
            'management_fee' => 'integer',
            'total_monthly_cost' => 'integer',
            'bedrooms' => 'integer',
            'bathrooms' => 'decimal:1',
            'square_feet' => 'integer',
            'status' => PropertyStatus::class,
        ];
    }

    public function bookings(): HasMany
    {
        return $this->hasMany(Booking::class);
    }

    protected static function newFactory()
    {
        return PropertyFactory::new();
    }

    public function statusState(): Attribute
    {
        return Attribute::make(
            get: fn(): PropertyState => $this->status->resolveState($this),
        );
    }
}
