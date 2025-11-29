<?php

namespace App\Repositories\Category;

use App\Models\Category;
use Prettus\Repository\Eloquent\BaseRepository;

class CategoryRepository extends BaseRepository implements CategoryRepositoryInterface
{
    /**
     * @return string
     */
    public function model(): string
    {
        return Category::class;
    }

    /**
     * Get list of categories with children structure
     * Returns: [{ id, label, children: [{ id, label }] }]
     */
    public function getList(): array
    {
        return Category::getList();
    }

    /**
     * Get category relations by ID
     * Returns parent or children based on category structure
     */
    public function getRelations(int $categoryId): ?array
    {
        return Category::getCategoryRelations($categoryId);
    }

    /**
     * Get all categories in flat structure
     *
     * @return array [{ id, name, parent_id }]
     */
    public function getAllFlat(?int $excludeId = null, ?int $categoryType = null): array
    {
        $query = Category::select(['id', 'name', 'parent_id'])
            ->when(!empty($categoryType), fn ($query) => $query->where('type', '=', $categoryType))
            ->orderBy('parent_id')
            ->orderBy('name');

        if ($excludeId) {
            $query->where('id', '!=', $excludeId);
        }

        return $query->get()->toArray();
    }

    /**
     * Get categories in tree structure
     * Returns full CategoryResourceInterface format with children
     */
    public function getTreeStructure(?int $excludeId, ?int $categoryType): array
    {
        $query = Category::query()->select([
            'id',
            'title',
            'content',
            'slug',
            'desc',
            'meta_title',
            'meta_desc',
            'meta_keywords',
            'status',
            'image_id',
            'type',
            'parent_id',
            'created_at',
            'updated_at',
        ])
            ->when(!empty($categoryType), fn ($query) => $query->where('type', '=', $categoryType))
            ->orderBy('parent_id')
            ->orderBy('title');

        if ($excludeId) {
            $excludeIds = $this->getDescendantIds($excludeId);
            $query->whereNotIn('id', $excludeIds);
        }

        $categories = $query->get()->toArray();

        return $this->buildTree($categories);
    }

    /**
     * Get all descendant IDs of a category (including the category itself)
     */
    private function getDescendantIds(int $categoryId): array
    {
        $descendants = [$categoryId];
        $children = Category::query()->where('parent_id', '=', $categoryId)->pluck('id')->toArray();

        foreach ($children as $childId) {
            $childDescendants = $this->getDescendantIds($childId);
            $descendants = array_merge($descendants, $childDescendants);
        }

        return $descendants;
    }

    /**
     * Build tree structure from flat array
     */
    private function buildTree(array $categories, ?int $parentId = null): array
    {
        $tree = [];

        foreach ($categories as $category) {
            if ($category['parent_id'] == $parentId) {
                $children = $this->buildTree($categories, $category['id']);
                if (!empty($children)) {
                    $category['children'] = $children;
                }
                $tree[] = $category;
            }
        }

        return $tree;
    }

    /**
     * @param array $ids
     * @return int
     */
    public function multiDelete(array $ids): int
    {
        return Category::query()->whereIn('id', $ids)->forceDelete();
    }
}
