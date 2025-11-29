<?php

namespace App\Services\Attribute;

use App\Enums\EnumAttributePropertyType;
use App\Models\Attribute;
use App\Repositories\Attribute\AttributeRepository;
use App\Services\BaseService;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Collection;

class AttributeService extends BaseService implements AttributeServiceInterface
{
    /**
     * @param AttributeRepository $productAttributeRepository
     */
    public function __construct(
        protected readonly AttributeRepository $productAttributeRepository,
    ) {
        $this->repository = $this->productAttributeRepository;
    }

    /**
     * @param string|null $search
     * @param int|null $limit
     * @return LengthAwarePaginator
     */
    public function search(?string $search = null, ?int $limit = null): LengthAwarePaginator
    {
        if (empty($search)) {
            return $this->repository
                ->paginate($limit);
        }

        return $this->repository->scopeQuery(function ($query) use ($search) {
            return $query->where('name', 'like', "%{$search}%");
        })->paginate($limit);
    }

    /**
     * Create a new product attribute.
     *
     * @param array $attributes
     * @return Attribute
     */
    public function create(array $attributes = []): Attribute
    {
        return DB::transaction(function () use ($attributes) {
            $record = $this->repository->create([
                'name' => $attributes['name'],
                'slug' => $attributes['slug'] ?: Str::slug($attributes['name']),
                'type' => $attributes['type'],
                'status' => $attributes['status'],
            ]);

            $this->createValues($record, $attributes['values'] ?? []);

            return $record;
        });
    }

    /**
     * @param Attribute $record
     * @param array $values
     * @return void
     */
    public function createValues(Attribute $record, array $values): void
    {
        $attributes = [];

        if (!empty($values)) {
            foreach ($values as $key => $value) {
                $attributes[$key] = [
                    'attribute_id' => $record->id,
                    'label' => $value['label'],
                    'value' => $value['value'],
                    'sort_order' => $key + 1,
                    'created_at' => now(),
                ];
            }
        }

        $this->repository->insertValues($attributes);
    }

    /**
     * Update an existing product attribute.
     *
     * @param array $attributes
     * @param int $id
     * @return Attribute
     */
    public function update(array $attributes, int $id): Attribute
    {
        $record = $this->repository->findOrFail($id);
        $record->load('values');
        $record->update($attributes);

        if (!$record->values) {
            return $record->refresh()->load(['values']);
        }

        $values = collect($attributes['values']);
        $values->filter(fn ($value) => !isset($value['id']))->each(function ($value, $key) use ($record) {
            $record->values()->create([
                'label' => $value['label'],
                'value' => $value['value'],
                'sort_order' => $key + 1,
            ]);
        });

        $updateRecords = $values->filter(fn ($value) => isset($value['id']));

        if ($updateRecords->isNotEmpty()) {
            $updateRecords->each(function ($value, $key) use ($record) {
                $attributeValue = $record->values->firstWhere('id', $value['id']);

                if ($attributeValue) {
                    $attributeValue->update([
                        'label' => $value['label'],
                        'value' => $value['value'],
                        'sort_order' => $key + 1,
                    ]);
                }
            });
        }

        $record->values->filter(fn ($value) => !in_array($value->id, $updateRecords->pluck('id')->toArray()))
            ->each(function ($value) {
                $value->delete();
            });

        return $record->refresh()->load(['values']);
    }

    /**
     * @param EnumAttributePropertyType $attributeType
     * @return Collection
     */
    public function getAttributeByAttributeType(EnumAttributePropertyType $attributeType): Collection
    {
        return $this->repository
            ->with(['values'])
            ->where([
                'attribute_type' => $attributeType->value,
            ])
            ->get();
    }
}
