<?php

namespace App\Repositories\Category;

interface CategoryRepositoryInterface
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
}
