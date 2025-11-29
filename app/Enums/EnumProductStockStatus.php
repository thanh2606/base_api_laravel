<?php

namespace App\Enums;

enum EnumProductStockStatus: int
{
    case IN_STOCK = 1;
    case OUT_OF_STOCK = 2;
    case ON_BACKORDER = 3;
}
