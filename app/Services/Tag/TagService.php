<?php

namespace App\Services\Tag;

use App\Enums\EnumTagType;
use App\Models\Tag;
use App\Repositories\Tag\TagRepository;
use App\Services\BaseService;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Arr;
use App\Constants\ConstPaginate;
use Illuminate\Database\Eloquent\Collection;

class TagService extends BaseService implements TagServiceInterface
{
    /**
     * @param TagRepository $tagRepository
     */
    public function __construct(
        private readonly TagRepository $tagRepository
    ) {
        $this->repository = $this->tagRepository;
    }

    /**
     * @param int $type
     * @param string|null $keyword
     * @param int|null $limit
     * @return LengthAwarePaginator
     */
    public function search(int $type, ?string $keyword, ?int $limit): LengthAwarePaginator
    {
        if (empty($keyword)) {
            return $this->repository
                ->scopeQuery(function ($query) use ($type) {
                    return $query->where('type', $type);
                })
                ->paginate($limit);
        }

        return $this->repository->scopeQuery(function ($query) use ($keyword, $type) {
            return $query
                ->where('type', $type)
                ->where('title', 'LIKE', "%{$keyword}%");
        })->paginate($limit);
    }


    /**
     * @return Collection
     */
    public function getAll(): Collection
    {
        return $this->tagRepository->getAll();
    }

    /**
     * @param int $type
     * @return Collection
     */
    public function getTagByType(int $type): Collection
    {
        return $this->tagRepository->getTagByType($type);
    }
}
