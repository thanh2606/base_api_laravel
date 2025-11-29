<?php

namespace App\Enums;

use App\Traits\EnumTrait;

enum EnumTagType: int
{
    use EnumTrait;

    case POST = 1;
    case PRODUCT = 2;
}
