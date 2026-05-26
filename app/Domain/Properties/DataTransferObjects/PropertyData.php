<?php

namespace Domain\Properties\DataTransferObjects;

use Domain\Properties\States\PropertyStatus;

readonly class PropertyData
{
    public function __construct(
        public string $name,
        public string $type,
        public string $address,
        public string $city,
        public string $state,
        public string $zip_code,
        public int $bedrooms,
        public float $bathrooms,
        public int $square_feet,
        public int $monthly_rent,
        public int $utilities_cost = 0,
        public int $management_fee = 0,
        public PropertyStatus $status = PropertyStatus::Available,
    ) {}

    public static function fromRequest(array $data): self
    {
        return new self(
            name: $data['name'],
            type: $data['type'],
            address: $data['address'],
            city: $data['city'],
            state: $data['state'],
            zip_code: $data['zip_code'],
            bedrooms: (int) $data['bedrooms'],
            bathrooms: (float) $data['bathrooms'],
            square_feet: (int) $data['square_feet'],
            monthly_rent: (int) ($data['monthly_rent'] * 100),
            utilities_cost: isset($data['utilities_cost'])
                ? (int) ($data['utilities_cost'] * 100)
                : 0,
            management_fee: isset($data['management_fee'])
                ? (int) ($data['management_fee'] * 100)
                : 0,
            status: isset($data['status'])
                ? PropertyStatus::from($data['status'])
                : PropertyStatus::Available,
        );
    }

    public function toArray(): array
    {
        return [
            'name' => $this->name,
            'type' => $this->type,
            'address' => $this->address,
            'city' => $this->city,
            'state' => $this->state,
            'zip_code' => $this->zip_code,
            'bedrooms' => $this->bedrooms,
            'bathrooms' => $this->bathrooms,
            'square_feet' => $this->square_feet,
            'monthly_rent' => $this->monthly_rent,
            'utilities_cost' => $this->utilities_cost,
            'management_fee' => $this->management_fee,
            'status' => $this->status->value,
        ];
    }
}
