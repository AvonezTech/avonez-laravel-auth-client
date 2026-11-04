<?php

namespace AvonezAuthClient\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

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
        return $this->belongsToMany(Organization::class)
            ->using(OrganizationUser::class)
            ->withPivot([
                'branch_id',
                'designation'
            ]);
    }

    public function branches(): BelongsToMany
    {
        return $this->belongsToMany(Branch::class, 'organization_user', 'user_id', 'branch_id')
            ->using(OrganizationUser::class)
            ->withPivot([
                'organization_id',
                'designation'
            ])
            ->withTimestamps();
    }

    public function organizationUsers(): HasMany
    {
        return $this->hasMany(OrganizationUser::class);
    }

    public function branchUsers(): HasMany
    {
        return $this->hasMany(OrganizationUser::class, 'branch_id');
    }
}
