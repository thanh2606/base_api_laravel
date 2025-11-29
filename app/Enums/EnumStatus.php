<?php

namespace App\Enums;

use App\Traits\EnumTrait;

enum EnumStatus: int
{
    use EnumTrait;

    case ACTIVE = 1;
    case INACTIVE = 0;

    public static function label(string $value): string
    {
        return match ($value) {
            self::ACTIVE->value => 'Active',
            default => 'Inactive',
        };
    }
}
