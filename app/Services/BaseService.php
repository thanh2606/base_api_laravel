<?php

namespace App\Services;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Model;
use Prettus\Repository\Contracts\RepositoryInterface;

abstract class BaseService
{
    protected RepositoryInterface $repository;

    public function create(array $attributes): Model
    {
        return $this->repository->create($attributes);
    }

    public function update(array $attributes, int $id): Model
    {
        return $this->repository->update($attributes, $id);
    }

    public function delete(int $id): int
    {
        return $this->repository->delete($id);
    }

    public function paginate(?int $limit = null): LengthAwarePaginator
    {
        return $this->repository->paginate($limit);
    }
}
