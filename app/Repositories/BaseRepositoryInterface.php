<?php

namespace App\Repositories;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;

interface BaseRepositoryInterface
{
    /**
     * @param array $filters
     * @param int|null $perPage
     * @return LengthAwarePaginator
     */
    public function paginate(array $filters = [], ?int $perPage = null): LengthAwarePaginator;

    public function getAll(): Collection;

    public function find(int $id): ?Model;

    public function create(array $attributes): Model;

    public function update(int $id, array $attributes): int;

    public function delete(int $id): bool;

    public function forceDelete(int $id): bool;

    public function restore(int $id): bool;

    public function findByField(string $field, $value, array $columns = ['*']): Collection;

    public function findWhere(array $where, array $columns = ['*']): Collection;

    public function findWhereFirst(array $where, array $columns = ['*']): ?Model;

    public function insert(array $attributes): bool;
}
