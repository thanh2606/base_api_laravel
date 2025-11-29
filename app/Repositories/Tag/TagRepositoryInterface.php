<?php

namespace App\Repositories\Tag;

use Illuminate\Database\Eloquent\Collection;

interface TagRepositoryInterface
{
    /**
     * @param int $type
     * @return Collection
     */
    public function getTagByType(int $type): Collection;

    /**
     * @return Collection
     */
    public function getAll(): Collection;
}
