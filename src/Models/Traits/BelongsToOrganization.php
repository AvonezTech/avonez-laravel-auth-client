<?php

namespace Avonez\AuthClient\Models\Traits;

trait BelongsToOrganization
{
    public function initializeBelongsToOrganization()
    {
        $this->fillable[] = 'organization_id';
    }

    public function organization()
    {
        $organizationModel = config('avonez-auth-client.organization-model');
        return $this->belongsTo($organizationModel, 'organization_id');
    }
}