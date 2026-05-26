<?php

namespace Domain\Tenants\Models;

use Database\Factories\TenantFactory;
use Domain\Bookings\Models\Booking;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Tenant extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'email',
        'phone',
    ];

    public function bookings(): HasMany
    {
        return $this->hasMany(Booking::class);
    }

    protected static function newFactory()
    {
        return TenantFactory::new();
    }
}
