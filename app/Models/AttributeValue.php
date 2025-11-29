<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property int $attribute_id
 * @property string $label
 * @property string $value
 * @property int $sort_order
 * @property Carbon $created_at
 * @property Carbon $updated_at
 */
class AttributeValue extends Model
{
    use HasFactory;

    protected $table = 'attribute_values';

    protected $fillable = [
        'attribute_id',
        'label',
        'value',
        'sort_order',
    ];
}
