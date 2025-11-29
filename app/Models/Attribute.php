<?php

namespace App\Models;

use App\Enums\EnumStatus;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property int $id
 * @property string $name
 * @property string $slug
 * @property int $type
 * @property int $attribute_type
 * @property int $sort_order
 * @property EnumStatus $status
 * @property Carbon $created_at
 * @property Carbon $updated_at
 */
class Attribute extends Model
{
    use HasFactory;

    protected $table = 'attributes';

    protected $fillable = [
        'name',
        'slug',
        'type',
        'attribute_type',
        'sort_order',
        'status',
    ];

    /**
     * @return HasMany
     */
    public function values(): HasMany
    {
        return $this->hasMany(AttributeValue::class, 'attribute_id')->orderBy('sort_order');
    }
}
