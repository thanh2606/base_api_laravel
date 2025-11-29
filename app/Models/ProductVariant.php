<?php

namespace App\Models;

use App\Traits\ImageRelationTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProductVariant extends Model
{
    use SoftDeletes;
    use ImageRelationTrait;

    protected $table = 'product_variants';

    protected $fillable = [
        'product_id',
        'sku',
        'title',
        'status',
        'price',
        'sale_price',
        'manage_stock',
        'stock_qty',
        'stock_status',
        'image_id',
        'is_default',
        'attributes',
    ];
}
