<?php

namespace App\Repositories\Media;

use Illuminate\Database\Eloquent\Collection;

interface MediaRepositoryInterface
{
    /**
     * @param array $ids
     * @return Collection
     */
    public function getByIds(array $ids): Collection;
}
