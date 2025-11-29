<?php

namespace App\Repositories\Tag;

use App\Models\Tag;
use Illuminate\Database\Eloquent\Collection;
use Prettus\Repository\Eloquent\BaseRepository;

class TagRepository extends BaseRepository implements TagRepositoryInterface
{
    public function model(): string
    {
        return Tag::class;
    }

    /**
     * @return Collection
     */
    public function getAll(): Collection
    {
        return Tag::query()->get();
    }

    /**
     * @param int $type
     * @return Collection
     */
    public function getTagByType(int $type): Collection
    {
        return Tag::query()
            ->where('type', $type)
            ->get();
    }
}
