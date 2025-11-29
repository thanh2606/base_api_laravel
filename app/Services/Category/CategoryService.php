<?php

namespace App\Services\Category;

use App\Repositories\Category\CategoryRepository;
use App\Services\BaseService;
use Illuminate\Pagination\LengthAwarePaginator;

class CategoryService extends BaseService implements CategoryServiceInterface
{
    /**
     * @param CategoryRepository $categoryRepository
     */
    public function __construct(
        protected readonly CategoryRepository $categoryRepository
    ) {
        $this->repository = $categoryRepository;
    }

    /**
     * Get list of categories with children structure
     * Returns: [{ id, label, children: [{ id, label }] }]
     */
    public function getList(): array
    {
        return $this->repository->getList();
    }

    /**
     * Get category relations by ID
     * Returns parent or children based on category structure
     */
    public function getRelations(int $categoryId): ?array
    {
        return $this->repository->getRelations($categoryId);
    }

    /**
     * Get categories in tree structure for frontend
     * Returns: [{ id, title, children: [{ id, title, children: [...] }] }]
     */
    public function getTreeStructure(?int $excludeId = null, ?int $categoryType = null): array
    {
        return $this->repository->getTreeStructure($excludeId, $categoryType);
    }

    /**
     * @param array $ids
     * @return int
     */
    public function multiDelete(array $ids): int
    {
        return $this->repository->multiDelete($ids);
    }

    /**
     * @param int $type
     * @param string|null $title
     * @param int|null $limit
     * @return LengthAwarePaginator
     */
    public function search(int $type, ?string $title = null, ?int $limit = null): LengthAwarePaginator
    {
        if (empty($title)) {
            return $this->repository
                ->scopeQuery(function ($query) use ($type) {
                    return $query->where('type', '=', $type);
                })
                ->orderBy('id', 'desc')
                ->paginate();
        }

        return $this->repository
            ->scopeQuery(function ($query) use ($type, $title) {
                return $query
                    ->where('type', '=', $type)
                    ->where('title', 'like', '%'.$title.'%');
            })
            ->orderBy('id', 'desc')
            ->paginate($limit);
    }
}
