<?php

namespace Domain\Properties\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
        'status',
    ];

    protected $casts = [
        'monthly_rent' => 'integer', 
        'bedrooms' => 'integer',
        'bathrooms' => 'decimal:1',
        'square_feet' => 'integer',
    ];
}