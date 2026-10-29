<?php

namespace AvonezAuthClient\Enums;

use Illuminate\Contracts\Support\Htmlable;

enum OrganizationType: string // implements HasColor, HasIcon, HasLabel
{
    case THIRD_PARTY = 'third_party';
    case FIRST_PARTY = 'first_party';
    case INTERNAL = 'internal';
    case PERSONAL = 'personal';

    public function getLabel(): string|Htmlable|null
    {
        return match ($this) {
            self::THIRD_PARTY => 'Third Party',
            self::FIRST_PARTY => 'First Party',
            self::INTERNAL => 'Internal',
            self::PERSONAL => 'Personal',
        };
    }

    public function getColor(): string|array|null
    {
        return match ($this) {
            self::THIRD_PARTY => 'info',
            self::FIRST_PARTY => 'success',
            self::INTERNAL => 'warning',
            self::PERSONAL => 'primary',
        };
    }

    public function getIcon(): string|null
    {
        return match ($this) {
            self::THIRD_PARTY => 'heroicon-o-globe-alt',
            self::FIRST_PARTY => 'heroicon-o-building-office',
            self::INTERNAL => 'heroicon-o-lock-closed',
            self::PERSONAL => 'heroicon-o-user',
        };
    }
}
