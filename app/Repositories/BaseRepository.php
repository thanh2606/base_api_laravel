<?php

namespace App\Repositories;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

interface BaseRepository
{
    /**
     * @param array $conditions
     * @return Collection
     */
    public function find(array $conditions = []): Collection;

    /**
     * @param int $id
     * @return Model
     */
    public function findById(int $id): Model;

    /**
     * @param array $attributes
     * @return Model
     */
    public function create(array $attributes): Model;

    /**
     * @param Model $model
     * @param array $attributes
     * @return mixed
     */
    public function update(Model $model, array $attributes = []);

    /**
     * @param Model $model
     * @return mixed
     */
    public function save(Model $model);

    /**
     * @param Model $model
     * @return mixed
     */
    public function delete(Model $model);
}
