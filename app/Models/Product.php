<?php

namespace App\Models;

use App\Traits\ImageRelationTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Product extends Model
{
    use SoftDeletes;
    use ImageRelationTrait;

    protected $table = 'products';

    protected $fillable = [
        'title',
        'slug',
        'desc',
        'short_desc',
        'content',
        'meta_title',
        'meta_desc',
        'meta_keywords',
        'sku',
        'type',
        'status',
        'sale_status',
        'price',
        'sale_price',
        'sale_price_start',
        'sale_price_end',
        'manage_stock',
        'stock_qty',
        'stock_status',
        'image_id',
        'download_link',
        'download_limit',
        'download_expiry',
        'view_count',
        'order_count',
    ];

    protected $appends = [
        'image',
    ];

    /**
     * @return BelongsToMany
     */
    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(Category::class, 'product_categories', 'product_id', 'category_id')
            ->withTimestamps();
    }

    /**
     * @return BelongsToMany
     */
    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(Tag::class, 'product_tags', 'product_id', 'tag_id')
            ->withTimestamps();
    }

    /**
     * @return BelongsToMany
     */
    public function galleries(): BelongsToMany
    {
        return $this->belongsToMany(Media::class, 'product_galleries', 'product_id', 'media_id')
            ->withTimestamps();
    }
}
