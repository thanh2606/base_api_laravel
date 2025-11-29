<?php

namespace App\Models;

use App\Enums\EnumStatus;
use App\Enums\EnumTagType;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property int $id
 * @property string $title
 * @property string $slug
 * @property string $meta_title
 * @property string $meta_desc
 * @property string $meta_keywords
 * @property int $status
 * @property int $type
 * @property int $author_id
 * @property int $image_id
 * @property string $image
 * @property Carbon $created_at
 * @property Carbon $updated_at
 */
class Tag extends Model
{
    use SoftDeletes;

    protected $table = 'tags';

    protected $fillable = [
        'title',
        'slug',
        'meta_title',
        'meta_desc',
        'meta_keywords',
        'status',
        'type',
        'author_id',
    ];

    protected $casts = [
        'status' => EnumStatus::class,
        'type' => EnumTagType::class,
    ];
}
