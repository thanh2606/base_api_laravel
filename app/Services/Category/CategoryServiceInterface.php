<?php

namespace App\Services\Category;

use Illuminate\Pagination\LengthAwarePaginator;

interface CategoryServiceInterface
{
    /**
     * Get list of categories with children structure
     * Returns: [{ id, label, children: [{ id, label }] }]
     */
    public function getList(): array;

    /**
     * Get category relations by ID
     * Returns parent or children based on category structure
     */
    public function getRelations(int $categoryId): ?array;

    /**
     * @param array $ids
     * @return integer
     */
    public function multiDelete(array $ids): int;

    /**
     * @param int $type
     * @param string|null $title
     * @param int|null $limit
     * @return LengthAwarePaginator
     */
    public function search(int $type, ?string $title = null, ?int $limit = null): LengthAwarePaginator;
}
