<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use App\Models\Category;
use App\Models\Post;

/**
 * @property int $id
 * @property string $name
 * @property string $path
 * @property string|null $image
 * @property int|null $width
 * @property int|null $height
 * @property string|null $mime_type
 * @property Carbon $created_at
 * @property Carbon $updated_at
 */
class Media extends Model
{
    protected $table = 'medias';

    protected $fillable = [
        'name',
        'path',
        'width',
        'height',
        'mime_type',
    ];

    protected function image(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->path ? asset('storage/'.$this->path) : null
        );
    }

    /**
     * Boot method to handle model events
     */
    protected static function boot()
    {
        parent::boot();
        static::deleting(function ($model) {
            $model->updateRelatedModels();
        });
    }

    /**
     * Update related models when media is deleted
     */
    private function updateRelatedModels()
    {
        $modelsToUpdate = [
            Category::class,
            Post::class,
        ];

        foreach ($modelsToUpdate as $modelClass) {
            if (class_exists($modelClass)) {
                $modelClass::where('image_id', $this->id)
                    ->update(['image_id' => null]);
            }
        }
    }

    /**
     * Get categories that use this media
     */
    public function categories()
    {
        return $this->hasMany(\App\Models\Category::class, 'image_id');
    }

    /**
     * Get posts that use this media
     */
    public function posts()
    {
        return $this->hasMany(\App\Models\Post::class, 'image_id');
    }

    /**
     * Get products that use this media
     */
    public function products()
    {
        return $this->hasMany(\App\Models\Product::class, 'image_id');
    }
}
