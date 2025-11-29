<?php

namespace App\Repositories\Post;

use App\Models\Post;

interface PostRepositoryInterface
{
    /**
     * @param int $id
     * @return Post
     */
    public function findOrFail(int $id): Post;
}
