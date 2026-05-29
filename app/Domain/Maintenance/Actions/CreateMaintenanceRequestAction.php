<?php

namespace Domain\Maintenance\Actions;

use Domain\Maintenance\DataTransferObjects\MaintenanceRequestData;
use Domain\Maintenance\Models\MaintenanceRequest;
use Domain\Maintenance\States\MaintenanceRequestStatus;

class CreateMaintenanceRequestAction
{
    public function execute(MaintenanceRequestData $data, ?int $tenantId = null): MaintenanceRequest
    {
        return MaintenanceRequest::create([
            'property_id' => $data->property_id,
            'tenant_id' => $tenantId,
            'title' => $data->title,
            'description' => $data->description,
            'priority' => $data->priority,
            'status' => MaintenanceRequestStatus::Reported,
        ]);
    }
}
