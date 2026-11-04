<?php

namespace AvonezAuthClient\Models;

use AvonezAuthClient\Enums\OrganizationStatus;
use AvonezAuthClient\Enums\OrganizationType;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Override;

class Organization extends Model
{
    protected $fillable = [
        'avonez_id',
        'name',
        'slug',
        'type',
        'status',
        'email',
        'phone',
        'website',
        'metadata',
    ];

    #[Override]
    protected function casts(): array
    {
        return [
            'type' => OrganizationType::class,
            'status' => OrganizationStatus::class,
            'metadata' => 'array',
        ];
    }

    // Relationships

    public function branches(): HasMany
    {
        $branchModel = config('avonez-auth-client.branch-model', Branch::class);
        return $this->hasMany($branchModel);
    }

    public function users(): BelongsToMany
    {
        $userModel = config('avonez-auth-client.user-model', User::class);
        $organizationUserModel = config('avonez-auth-client.organization-user-model', OrganizationUser::class);

        return $this->belongsToMany($userModel, 'organization_user', 'organization_id', 'user_id')
            ->using($organizationUserModel)
            ->withPivot([
                'branch_id',
                'designation'
            ]);
    }

    public function organizationUsers(): HasMany
    {
        $organizationUserModel = config('avonez-auth-client.organization-user-model', OrganizationUser::class);
    
        return $this->hasMany($organizationUserModel);
    }
}
