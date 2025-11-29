<?php

namespace App\Repositories;

use App\Constants\ConstPaginate;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;

abstract class BaseRepository implements BaseRepositoryInterface
{
    protected Model $model;

    public function getAll(): Collection
    {
        return $this->model::query()->get();
    }

    public function find(int $id): ?Model
    {
        return $this->model::query()->find($id);
    }

    public function findOrFail(int $id): Model
    {
        return $this->model::query()->findOrFail($id);
    }

    public function create(array $attributes): Model
    {
        return $this->model::query()->create($attributes);
    }

    public function update(int $id, array $attributes): int
    {
        return $this->model::query()->where('id', $id)->update($attributes);
    }

    public function delete(int $id): bool
    {
        return $this->model::query()->where('id', $id)->delete();
    }

    public function forceDelete(int $id): bool
    {
        $record = $this->model->withTrashed()->find($id);
        if ($record) {
            return $record->forceDelete();
        }

        return false;
    }

    public function restore($id): bool
    {
        $record = $this->model->withTrashed()->find($id);
        if ($record) {
            return $record->restore();
        }

        return false;
    }

    public function findByField(string $field, $value, array $columns = ['*']): Collection
    {
        return $this->model::query()->where($field, $value)->get($columns);
    }

    public function findWhere(array $where, array $columns = ['*']): Collection
    {
        $query = $this->model::query();

        foreach ($where as $field => $value) {
            if (is_array($value)) {
                [$field, $condition, $val] = $value;
                $query = $query->where($field, $condition, $val);
            } else {
                $query = $query->where($field, $value);
            }
        }

        return $query->get($columns);
    }

    public function findWhereFirst(array $where, array $columns = ['*']): ?Model
    {
        $query = $this->model::query();

        foreach ($where as $field => $value) {
            if (is_array($value)) {
                [$field, $condition, $val] = $value;
                $query = $query->where($field, $condition, $val);
            } else {
                $query = $query->where($field, $value);
            }
        }

        return $query->first($columns);
    }

    /**
     * @param array $attributes
     * @return bool
     */
    public function insert(array $attributes): bool
    {
        return $this->model::query()->insert($attributes);
    }

    /**
     * @param array $filters
     * @param int|null $perPage
     * @return LengthAwarePaginator
     */
    public function paginate(array $filters = [], ?int $perPage = null): LengthAwarePaginator
    {
        if (empty($perPage)) {
            $perPage = ConstPaginate::PER_PAGE;
        }

        return $this->model::query()->where($filters)
            ->orderByDesc('id')
            ->paginate($perPage);
    }
}
