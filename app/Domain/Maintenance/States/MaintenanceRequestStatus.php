<?php

namespace Domain\Maintenance\States;

enum MaintenanceRequestStatus: string
{
    case Reported = 'reported';
    case Assigned = 'assigned';
    case InProgress = 'in_progress';
    case Resolved = 'resolved';

    public function label(): string
    {
        return match ($this) {
            self::Reported => 'Reported',
            self::Assigned => 'Assigned',
            self::InProgress => 'In Progress',
            self::Resolved => 'Resolved',
        };
    }

    public function color(): string
    {
        return match ($this) {
            self::Reported => 'red',
            self::Assigned => 'orange',
            self::InProgress => 'blue',
            self::Resolved => 'green',
        };
    }

    public function canTransitionTo(self $target): bool
    {
        return match ($this) {
            self::Reported => in_array($target, [self::Assigned, self::Resolved], true),
            self::Assigned => in_array($target, [self::InProgress, self::Resolved], true),
            self::InProgress => $target === self::Resolved,
            self::Resolved => false,
        };
    }

    public function availableTransitions(): array
    {
        return array_values(array_filter(
            self::cases(),
            fn (self $s) => $this->canTransitionTo($s),
        ));
    }
}
