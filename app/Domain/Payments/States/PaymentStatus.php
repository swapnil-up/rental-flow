<?php

namespace Domain\Payments\States;

enum PaymentStatus: string
{
    case Pending = 'pending';
    case Paid = 'paid';
    case Overdue = 'overdue';
    case Refunded = 'refunded';

    public function label(): string
    {
        return match ($this) {
            self::Pending => 'Pending',
            self::Paid => 'Paid',
            self::Overdue => 'Overdue',
            self::Refunded => 'Refunded',
        };
    }

    public function color(): string
    {
        return match ($this) {
            self::Pending => 'orange',
            self::Paid => 'green',
            self::Overdue => 'red',
            self::Refunded => 'gray',
        };
    }
}
