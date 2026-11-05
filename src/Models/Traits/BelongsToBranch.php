<?php

namespace AvonezAuthClient\Models\Traits;


trait BelongsToBranch
{
    public function initializeBelongsToBranch()
    {
        $this->fillable[] = 'branch_id';
    }

    public function branch()
    {
        $branchModel = config('avonez-auth-client.branch-model');
        return $this->belongsTo($branchModel, 'branch_id');
    }
}