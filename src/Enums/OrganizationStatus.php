<?php

namespace AvonezAuthClient\Enums;


use Illuminate\Contracts\Support\Htmlable;

enum OrganizationStatus: string
{
    case ACTIVE = 'active';
    case SUSPENDED = 'suspended';
    case PENDING = 'pending';

    public function getLabel(): string|Htmlable|null
    {
        return match ($this) {
            self::ACTIVE => 'Active',
            self::SUSPENDED => 'Suspended',
            self::PENDING => 'Pending',
        };
    }

    public function getColor(): string|array|null
    {
        return match ($this) {
            self::ACTIVE => 'success',
            self::SUSPENDED => 'danger',
            self::PENDING => 'warning',
        };
    }

    public function getIcon(): string|null
    {
        return match ($this) {
            self::ACTIVE => 'heroicon-o-check-circle',
            self::SUSPENDED => 'heroicon-o-x-circle',
            self::PENDING => 'heroicon-o-clock',
        };
    }
}
