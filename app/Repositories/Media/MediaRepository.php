<?php

namespace App\Repositories\Media;

use App\Models\Media;
use Prettus\Repository\Eloquent\BaseRepository;
use Illuminate\Database\Eloquent\Collection;

class MediaRepository extends BaseRepository implements MediaRepositoryInterface
{

    /**
     * @return string
     */
    public function model(): string
    {
        return Media::class;
    }

    /**
     * @param array $ids
     * @return Collection<Media>
     */
    public function getByIds(array $ids): Collection
    {
        return $this->model::query()->whereIn('id', $ids)->get();
    }
}
