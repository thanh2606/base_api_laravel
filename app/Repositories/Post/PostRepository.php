<?php

namespace App\Repositories\Post;


use App\Models\Post;
use Prettus\Repository\Eloquent\BaseRepository;
use Illuminate\Database\Eloquent\Collection;

class PostRepository extends BaseRepository implements PostRepositoryInterface
{
    public function model(): string
    {
        return Post::class;
    }

    /**
     * @param array $ids
     * @return Collection
     */
    public function getByIds(array $ids): Collection
    {
        return Post::query()
            ->with(['categories', 'media'])
            ->whereIn('id', $ids)->get();
    }

    /**
     * @param int $id
     * @return Post
     */
    public function findOrFail(int $id): Post
    {
        return $this->model::query()->findOrFail($id);
    }
}
