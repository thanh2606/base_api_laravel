<?php

namespace App\Enums;

use App\Traits\EnumTrait;

enum EnumCategoryType: int
{
    use EnumTrait;

    case POST = 1;
    case PRODUCT = 2;
}
