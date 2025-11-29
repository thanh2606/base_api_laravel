<?php

namespace App\Enums;

use App\Traits\EnumTrait;

enum EnumProductType: int
{
    use EnumTrait;

    case SIMPLE = 1;
    case VARIABLE = 2;
    case VIRTUAL = 3;
}
