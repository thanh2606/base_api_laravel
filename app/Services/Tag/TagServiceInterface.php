<?php

namespace App\Services\Tag;

use App\Constants\ConstPaginate;
use App\Enums\EnumTagType;
use App\Models\Tag;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

interface TagServiceInterface
{
    /**
     * @return Collection
     */
    public function getAll(): Collection;

    /**
     * @param int $type
     * @return Collection
     */
    public function getTagByType(int $type): Collection;

    /**
     * @param int $type
     * @param string|null $keyword
     * @param int|null $limit
     * @return LengthAwarePaginator
     */
    public function search(int $type, ?string $keyword, ?int $limit): LengthAwarePaginator;
}
