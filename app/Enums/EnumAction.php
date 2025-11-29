<?php

namespace App\Enums;

use App\Traits\EnumTrait;

enum EnumAction: int
{
    use EnumTrait;
    case VIEW = 1;
    case CREATE = 2;

    case EDIT = 3;

    case DELETE = 4;
}
