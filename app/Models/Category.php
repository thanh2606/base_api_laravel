<?php

namespace App\Models;

use App\Enums\EnumCategoryType;
use App\Enums\EnumStatus;
use App\Traits\ImageRelationTrait;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property int $id
 * @property string $title
 * @property string $slug
 * @property string $desc
 * @property string $content
 * @property string $meta_title
 * @property string $meta_desc
 * @property string $meta_keywords
 * @property int $status
 * @property int $type
 * @property int $parent_id
 * @property int $author_id
 * @property int $image_id
 * @property string $image
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property Category $parent
 */
class Category extends Model
{
    use HasFactory, ImageRelationTrait, SoftDeletes;

    protected $table = 'categories';

    protected $fillable = [
        'title',
        'slug',
        'desc',
        'content',
        'meta_title',
        'meta_desc',
        'meta_keywords',
        'status',
        'type',
        'parent_id',
        'author_id',
        'image_id',
    ];

    protected $appends = [
        'image',
    ];

    protected $casts = [
        'status' => EnumStatus::class,
        'type' => EnumCategoryType::class,
    ];

    /**
     * Get the parent category
     */
    public function parent()
    {
        return $this->belongsTo(Category::class, 'parent_id');
    }

    /**
     * Get direct children categories
     */
    public function children()
    {
        return $this->hasMany(Category::class, 'parent_id');
    }

    /**
     * Get list of categories with children structure
     * Returns: [{ id, label, children: [{ id, label }] }]
     */
    public static function getList(): array
    {
        return static::query()->whereNull('parent_id')
            ->orderBy('title')
            ->with(['children' => function ($query) {
                $query->orderBy('title');
            }])
            ->get()
            ->map(function ($category) {
                return [
                    'id' => $category->id,
                    'label' => $category->title,
                    'children' => $category->children->map(function ($child) {
                        return [
                            'id' => $child->id,
                            'label' => $child->title,
                        ];
                    })->toArray(),
                ];
            })
            ->toArray();
    }

    /**
     * Get category relations by ID
     * Returns parent or children based on category structure
     */
    public static function getCategoryRelations($categoryId)
    {
        $category = static::with(['parent', 'children'])->find($categoryId);

        if (!$category) {
            return;
        }

        $result = [
            'id' => $category->id,
            'label' => $category->title,
        ];

        // If has parent, return parent info
        if ($category->parent) {
            $result['parent'] = [
                'id' => $category->parent->id,
                'label' => $category->parent->title,
            ];
        }

        // If has children, return children info
        if ($category->children->count() > 0) {
            $result['children'] = $category->children->map(function ($child) {
                return [
                    'id' => $child->id,
                    'label' => $child->title,
                ];
            })->toArray();
        }

        return $result;
    }
}
