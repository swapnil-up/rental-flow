<?php

namespace Domain\Expenses\Models;

use Database\Factories\ExpenseFactory;
use Domain\Properties\Models\Property;
use Domain\Expenses\States\ExpenseCategory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Expense extends Model
{
    use HasFactory;

    protected $fillable = [
        'property_id',
        'category',
        'amount',
        'description',
        'date',
    ];

    protected function casts(): array
    {
        return [
            'amount' => 'integer',
            'date' => 'date',
            'category' => ExpenseCategory::class,
        ];
    }

    public function property(): BelongsTo
    {
        return $this->belongsTo(Property::class);
    }

    protected static function newFactory()
    {
        return ExpenseFactory::new();
    }
}
