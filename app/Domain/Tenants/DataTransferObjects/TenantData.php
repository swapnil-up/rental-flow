<?php

namespace Domain\Tenants\DataTransferObjects;

readonly class TenantData
{
    public function __construct(
        public string $name,
        public string $email,
        public ?string $phone,
    ) {}

    public static function fromRequest(array $data): self
    {
        return new self(
            name: $data['name'],
            email: $data['email'],
            phone: $data['phone'] ?? null,
        );
    }

    public function toArray(): array
    {
        return [
            'name' => $this->name,
            'email' => $this->email,
            'phone' => $this->phone,
        ];
    }
}
