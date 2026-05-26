<?php

namespace Domain\Tenants\Actions;

use Domain\Tenants\DataTransferObjects\TenantData;
use Domain\Tenants\Models\Tenant;

class CreateTenantAction
{
    public function execute(TenantData $data): Tenant
    {
        return Tenant::create($data->toArray());
    }
}
