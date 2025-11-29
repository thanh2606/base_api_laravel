<?php

namespace App\Services\Post;

use App\Models\Post;
use App\Repositories\Post\PostRepository;
use App\Services\BaseService;
use App\Traits\UploadTrait;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;

class PostService extends BaseService implements PostServiceInterface
{
    use UploadTrait;

    /**
     * @param PostRepository $postRepository
     */
    public function __construct(
        protected PostRepository $postRepository
    ) {
        $this->repository = $this->postRepository;
    }

    /**
     * @param string|null $keyword
     * @param int|null $limit
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function search(?string $keyword = null, ?int $limit = null): LengthAwarePaginator
    {
        if (empty($keyword)) {
            return $this->repository->paginate($limit);
        }

        return $this->repository
            ->scopeQuery(function ($query) use ($keyword) {
                return $query->where('title', 'LIKE', "%{$keyword}%");
            })
            ->paginate($limit);
    }

    /**
     * @param array $attributes
     * @return Post
     */
    public function create(array $attributes): Post
    {
        return DB::transaction(function () use ($attributes) {
            $post = $this->postRepository->create($attributes);
            $categoryIds = Arr::get($attributes, 'category_ids', []);
            $tagIds = Arr::get($attributes, 'tag_ids', []);

            if (!empty($categoryIds)) {
                $post->categories()->attach($categoryIds);
            }

            if (!empty($tagIds)) {
                $post->tags()->attach($tagIds);
            }

            return $post;
        });
    }

    /**
     * @param array $attributes
     * @param int $id
     * @return Post
     */
    public function update(array $attributes, int $id): Post
    {
        return DB::transaction(function () use ($id, $attributes) {
            $post = $this->repository->update($attributes, $id);
            $categoryIds = Arr::get($attributes, 'category_ids', []);
            $tagIds = Arr::get($attributes, 'tag_ids', []);

            if (isset($categoryIds)) {
                $post->categories()->sync($categoryIds);
            }

            if (isset($tagIds)) {
                $post->tags()->sync($tagIds);
            }

            return $post;
        });
    }

    /**
     * @param int $id
     * @return int
     */
    public function delete(int $id): int
    {
        $post = $this->repository->findOrFail($id);

        return DB::transaction(function () use ($post) {
            $post->categories()->detach();
            $post->tags()->detach();

            if (!empty($post->media)) {
                $this->deleteByPath($post->media->path);
            }

            $post->media()->delete();
            return $post->delete();
        });
    }

    /**
     * @param array $ids
     * @return void
     */
    public function multiDelete(array $ids): void
    {
        $posts = $this->postRepository->getByIds($ids);
        DB::transaction(function () use ($posts) {
            foreach ($posts as $post) {
                $this->delete($post);
            }
        });
    }
}
