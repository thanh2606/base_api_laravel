<?php

namespace App\Http\Controllers\Admin;

use App\Constants\ConstPaginate;
use App\Enums\EnumCategoryType;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Post\StorePostRequest;
use App\Http\Requests\Admin\Post\UpdatePostRequest;
use App\Http\Resources\Common\MetaResource;
use App\Models\Post;
use App\Services\Category\CategoryService;
use App\Services\Post\PostService;
use Illuminate\Http\RedirectResponse;
use App\Services\Tag\TagService;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class PostController extends Controller
{
    /**
     * @param PostService $postService
     * @param CategoryService $categoryService
     * @param TagService $tagService
     */
    public function __construct(
        protected readonly PostService $postService,
        protected readonly CategoryService $categoryService,
        protected readonly TagService $tagService
    ) {}

    /**
     * @param Request $request
     * @return Response
     */
    public function index(Request $request): Response
    {
        $keyword = $request->query('search');
        $limit = $request->query('limit', ConstPaginate::PER_PAGE);

        $records = $this->postService->search($keyword, $limit);

        return Inertia::render('post/Index', [
            'records' => $records->items(),
            'meta' => MetaResource::make($records),
        ]);
    }

    /**
     * @return Response
     */
    public function create(): Response
    {
        $categories = $this->categoryService->getTreeStructure(
            categoryType: EnumCategoryType::POST->value
        );
        $tags = $this->tagService->getAll();

        return Inertia::render('post/Create', [
            'categories' => $categories,
            'tags' => $tags,
        ]);
    }

    /**
     * @param StorePostRequest $request
     * @return RedirectResponse
     */
    public function store(StorePostRequest $request): RedirectResponse
    {
        $record = $this->postService->create($request->toArray());

        return redirect()->route('admin.posts.show', $record->id)
            ->with('success', 'Bài viết đã được tạo thành công.');
    }

    /**
     * @param Post $post
     * @return Response
     */
    public function show(Post $post): Response
    {
        $post->load(['categories', 'tags']);
        $categories = $this->categoryService->getTreeStructure(
            categoryType: EnumCategoryType::POST->value
        );
        $tags = $this->tagService->getAll();

        return Inertia::render('post/Show', [
            'post' => $post,
            'categories' => $categories,
            'tags' => $tags,
            'selectCategories' => $post->categories->pluck('id')->toArray(),
            'selectTags' => $post->tags->pluck('id')->toArray(),
        ]);
    }

    /**
     * @param Post $post
     * @param UpdatePostRequest $request
     * @return RedirectResponse
     */
    public function update(Post $post, UpdatePostRequest $request): RedirectResponse
    {
        $record = $this->postService->update($request->validated(), $post->id);

        return redirect()->route('admin.posts.show', $record->id)
            ->with('success', 'Bài viết đã được cập nhật thành công.');
    }

    /**
     * @param Post $post
     * @return RedirectResponse
     */
    public function destroy(Post $post): RedirectResponse
    {
        try {
            $this->postService->delete($post->id);

            return redirect()->route('admin.posts.index')
                ->with('success', 'Xóa bài viết thành công.');
        } catch (\Throwable $exception) {
            return redirect()->back()->withErrors(['message' => 'Có lỗi xảy ra']);
        }
    }

    /**
     * @return RedirectResponse
     */
    public function multiDelete(): RedirectResponse
    {
        $ids = request()->input('ids', []);
        $this->postService->multiDelete($ids);

        return redirect()->route('admin.posts.index')
            ->with('success', 'Xóa bài viết thành công.');
    }
}
