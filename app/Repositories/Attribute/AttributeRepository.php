<?php

namespace App\Repositories\Attribute;

use App\Models\Attribute;
use App\Models\AttributeValue;
use Prettus\Repository\Eloquent\BaseRepository;

class AttributeRepository extends BaseRepository implements AttributeRepositoryInterface
{
    /**
     * @return string
     */
    public function model(): string
    {
        return Attribute::class;
    }

    /**
     * @param array $attributes
     * @return bool
     */
    public function insertValues(array $attributes = []): bool
    {
        return AttributeValue::query()->insert($attributes);
    }

    /**
     * @param int $id
     * @return Attribute
     */
    public function findOrFail(int $id): Attribute
    {
        return $this->model::query()->findOrFail($id);
    }
}
