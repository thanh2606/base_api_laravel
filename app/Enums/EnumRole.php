<?php

namespace App\Enums;

use App\Traits\EnumTrait;

enum EnumRole: int
{
    use EnumTrait;

    case SUPER_ADMIN = 1;
    case ADMIN = 2;
    case EDITOR = 3;

    public static function label(string $value): string
    {
        return match ($value) {
            self::SUPER_ADMIN->value => 'Super admin',
            self::ADMIN->value => 'Admin',
            default => 'Editor',
        };
    }
}
