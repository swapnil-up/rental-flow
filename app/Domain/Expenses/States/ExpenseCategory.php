<?php

namespace Domain\Expenses\States;

enum ExpenseCategory: string
{
    case Repairs = 'repairs';
    case Maintenance = 'maintenance';
    case Utilities = 'utilities';
    case Insurance = 'insurance';
    case Tax = 'tax';
    case Other = 'other';

    public function label(): string
    {
        return match ($this) {
            self::Repairs => 'Repairs',
            self::Maintenance => 'Maintenance',
            self::Utilities => 'Utilities',
            self::Insurance => 'Insurance',
            self::Tax => 'Tax',
            self::Other => 'Other',
        };
    }
}
