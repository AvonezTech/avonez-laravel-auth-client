<?php

namespace AvonezAuthClient\Models;

use AvonezAuthClient\Enums\OrganizationStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;
use Override;

class Branch extends Model
{
    protected $fillable = [
        'avonez_id',
        'organization_id',
        'name',
        'slug',
        'address',
        'status',
        'email',
        'phone',
        'metadata',
    ];

    #[Override]
    protected function casts(): array
    {
        return [
            'status' => OrganizationStatus::class,
            'metadata' => 'array',
        ];
    }

    #[Override]
    protected static function booted(): void
    {
        static::creating(function (Branch $branch) {
            if (! $branch->slug) {
                $branch->slug = static::createSlug($branch->name);
            }
            if (! $branch->metadata) {
                $branch->metadata = [];
            }
        });
    }
    public static function createSlug(string $name): string
    {
        $base = Str::slug($name);
        $candidate = $base;
        $candidate .= '-' . Str::random(10);
        return $candidate;
    }

    // Relationships

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'organization_user', 'branch_id', 'user_id')
            ->using(OrganizationUser::class)
            ->withPivot([
                'organization_id',
                'designation'
            ])
            ->withTimestamps();
    }

    public function branchUsers(): HasMany
    {
        return $this->hasMany(OrganizationUser::class, 'branch_id');
    }
}
