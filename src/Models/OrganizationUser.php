<?php

namespace AvonezAuthClient\Models;

use Illuminate\Database\Eloquent\Concerns\HasTimestamps;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\Pivot;

class OrganizationUser extends Pivot
{
    use HasTimestamps;
    
    public $incrementing = true;


    protected $fillable = [
        'organization_id',
        'branch_id',
        'user_id',
        'designation',
    ];

    // Relationships

    public function organization(): BelongsTo
    {
        $organizationModel = config('avonez-auth-client.organization-model', Organization::class);
        return $this->belongsTo($organizationModel);
    }

    public function user(): BelongsTo
    {
        $userModel = config('avonez-auth-client.user-model', User::class);
        return $this->belongsTo($userModel);
    }

    public function branch(): BelongsTo
    {
        $branchModel = config('avonez-auth-client.branch-model', Branch::class);
        return $this->belongsTo($branchModel);
    }
}
