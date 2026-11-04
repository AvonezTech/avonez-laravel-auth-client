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
        'slug',
        'type',
        'status',
        'email',
        'phone',
        'website',
        'metadata',
    ];

    #[Override]
    protected function casts()
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
        return $this->hasMany(Branch::class);
    }

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class)
            ->using(OrganizationUser::class)
            ->withPivot([
                'branch_id',
                'designation'
            ]);
    }

    public function organizationUsers(): HasMany
    {
        return $this->hasMany(OrganizationUser::class);
    }
}
