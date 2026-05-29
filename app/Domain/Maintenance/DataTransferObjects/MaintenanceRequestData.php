<?php

namespace Domain\Maintenance\DataTransferObjects;

readonly class MaintenanceRequestData
{
    public function __construct(
        public int $property_id,
        public string $title,
        public string $description,
        public string $priority,
    ) {}

    public static function fromRequest(array $data): self
    {
        return new self(
            property_id: (int) $data['property_id'],
            title: $data['title'],
            description: $data['description'],
            priority: $data['priority'],
        );
    }
}
