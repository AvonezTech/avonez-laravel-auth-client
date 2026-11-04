<?php

namespace AvonezAuthClient\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    protected $table = 'users';

    protected $fillable = [
        'avonez_id',
        'name',
        'mobile_number',
        'email',
        'mobile_number_verified',
        'email_verified',
        'access_token',
        'refresh_token',
        'meta'
    ];

    protected $hidden = [
        'access_token',
        'refresh_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'mobile_number_verified' => 'boolean',
            'email_verified' => 'boolean',
            'meta' => 'array',
        ];
    }

    protected static function booted(): void
    {
        static::saving(function ($user) {
            if (is_null($user->meta)) {
                $user->meta = [];
            }
        });
    }

    // Relationships

    public function organizations(): BelongsToMany
    {
        $organizationModel = config('avonez-auth-client.organization-model', Organization::class);
        $organizationUserModel = config('avonez-auth-client.organization-user-model', OrganizationUser::class);

        return $this->belongsToMany($organizationModel, 'organization_user', 'user_id', 'organization_id')
            ->using($organizationUserModel)
            ->withPivot([
                'branch_id',
                'designation'
            ]);
    }

    public function branches(): BelongsToMany
    {
        $branchModel = config('avonez-auth-client.branch-model', Branch::class);
        $organizationUserModel = config('avonez-auth-client.organization-user-model', OrganizationUser::class);

        return $this->belongsToMany($branchModel, 'organization_user', 'user_id', 'branch_id')
            ->using($organizationUserModel)
            ->withPivot([
                'organization_id',
                'designation'
            ])
            ->withTimestamps();
    }

    public function organizationUsers(): HasMany
    {
        $organizationUserModel = config('avonez-auth-client.organization-user-model', OrganizationUser::class);
        return $this->hasMany($organizationUserModel);
    }

    public function branchUsers(): HasMany
    {
        $organizationUserModel = config('avonez-auth-client.organization-user-model', OrganizationUser::class);
        return $this->hasMany($organizationUserModel, 'branch_id');
    }
}
