<?php

namespace Domain\Tenants\Models;

use App\Models\User;
use Database\Factories\TenantFactory;
use Domain\Bookings\Models\Booking;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Notifications\Notifiable;

class Tenant extends Model
{
    use HasFactory, Notifiable;

    public function routeNotificationForMail(): string
    {
        return $this->email;
    }

    protected $fillable = [
        'name',
        'email',
        'phone',
        'user_id',
    ];

    public function bookings(): HasMany
    {
        return $this->hasMany(Booking::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    protected static function newFactory()
    {
        return TenantFactory::new();
    }
}
