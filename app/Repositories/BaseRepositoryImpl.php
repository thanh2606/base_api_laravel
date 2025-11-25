<?php

namespace App\Repositories;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

class BaseRepositoryImpl implements BaseRepository
{
    /**
     * @var Model
     */
    protected $model;

    /**
     * AbstractRepository constructor.
     *
     * @param Model $model
     */
    public function __construct(Model $model)
    {
        $this->model = $model;
    }

    /**
     * @param array $conditions
     * @return Collection
     */
    public function find(array $conditions = []): Collection
    {
        return $this->model->where($conditions)->get();
    }

    /**
     * @param int $id
     * @return Model
     */
    public function findById(int $id): Model
    {
        return $this->model->findOrFail($id);
    }

    /**
     * @param array $attributes
     * @return Model
     */
    public function create(array $attributes): Model
    {
        return $this->model->create($attributes);
    }

    /**
     * @param Model $model
     * @param array $attributes
     * @return bool|mixed
     */
    public function update(Model $model, array $attributes = [])
    {
        return $model->update($attributes);
    }

    /**
     * @param Model $model
     * @return bool|mixed
     */
    public function save(Model $model)
    {
        return $model->save();
    }

    /**
     * @param Model $model
     * @return bool|mixed|null
     */
    public function delete(Model $model)
    {
        return $model->delete();
    }
}
