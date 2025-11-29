<?php

namespace App\Services\Attribute;

use App\Models\Attribute;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use App\Enums\EnumAttributePropertyType;

interface AttributeServiceInterface
{
    /**
     * @param Attribute $record
     * @param array $values
     * @return void
     */
    public function createValues(Attribute $record, array $values): void;

    /**
     * @param string|null $search
     * @param int|null $limit
     * @return LengthAwarePaginator
     */
    public function search(?string $search = null, ?int $limit = null): LengthAwarePaginator;

    /**
     * @param EnumAttributePropertyType $attributeType
     * @return Collection
     */
    public function getAttributeByAttributeType(EnumAttributePropertyType $attributeType): Collection;
}
