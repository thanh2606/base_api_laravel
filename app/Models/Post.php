<?php

namespace App\Models;

use App\Enums\EnumStatus;
use App\Traits\ImageRelationTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Post extends Model
{
    use ImageRelationTrait, SoftDeletes;

    protected $table = 'posts';

    protected $fillable = [
        'title',
        'slug',
        'desc',
        'content',
        'status',
        'meta_title',
        'meta_desc',
        'meta_keywords',
        'status',
        'author_id',
        'image_id',
    ];

    protected $appends = [
        'image',
    ];

    protected $casts = [
        'status' => EnumStatus::class,
    ];

    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(Category::class, 'post_categories', 'post_id', 'category_id')
            ->withTimestamps();
    }

    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(Tag::class, 'post_tags', 'post_id', 'tag_id')
            ->withTimestamps();
    }
}
