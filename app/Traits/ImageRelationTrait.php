<?php

namespace App\Traits;

use App\Models\Media;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

trait ImageRelationTrait
{
    /**
     * Get the media/image relationship
     */
    public function media(): BelongsTo
    {
        return $this->belongsTo(Media::class, 'image_id');
    }

    /**
     * Get image URL from related media
     */
    protected function image(): ?Attribute
    {
        return Attribute::make(
            get: function () {
                if (empty($this->media)) {
                    return null;
                }

                return $this->media->image;
            }
        );
    }

    public function hasImage(): bool
    {
        return !is_null($this->image_id) && $this->media !== null;
    }
}
