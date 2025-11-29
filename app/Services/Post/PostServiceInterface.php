<?php

namespace App\Services\Post;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface PostServiceInterface
{
    /**
     * @param string|null $keyword
     * @param int|null $limit
     * @return LengthAwarePaginator
     */
    public function search(?string $keyword = null, ?int $limit = null): LengthAwarePaginator;
}
