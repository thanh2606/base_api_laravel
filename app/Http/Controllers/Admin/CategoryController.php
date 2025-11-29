<?php

namespace App\Http\Controllers\Admin;

use App\Constants\ConstPaginate;
use App\Enums\EnumCategoryType;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Category\SearchCategoryRequest;
use App\Http\Requests\Admin\Category\StoreCategoryRequest;
use App\Http\Requests\Admin\Category\UpdateCategoryRequest;
use App\Http\Resources\Common\MetaResource;
use App\Models\Category;
use App\Services\Category\CategoryService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Validation\ValidationException;
use Inertia\Inertia;
use Inertia\Response;

class CategoryController extends Controller
{
    /**
     * @param CategoryService $categoryService
     */
    public function __construct(
        protected readonly CategoryService $categoryService
    ) {}

    /**
     * @param SearchCategoryRequest $request
     * @return Response
     */
    public function index(SearchCategoryRequest $request): Response
    {
        $view = 'category/Index';
        $title = $request->input('search');
        $type = $request->integer('type');
        $limit = $request->query('perPage', ConstPaginate::PER_PAGE);
        $records = $this->categoryService->search($type, $title, $limit);

        if (EnumCategoryType::PRODUCT->is($type)) {
            $view = 'product-category/Index';
        }

        return Inertia::render($view, [
            'type' => $type,
            'records' => $records->items(),
            'meta' => MetaResource::make($records),
        ]);
    }

    /**
     * @return Response
     */
    public function create(): Response
    {
        $type = request()->integer('type');
        $categories = $this->categoryService->getTreeStructure(
            categoryType: $type
        );

        $view = 'category/Create';

        if (EnumCategoryType::PRODUCT->is($type)) {
            $view = 'product-category/Create';
        }

        return Inertia::render($view, [
            'categories' => $categories,
            'type' => $type,
        ]);
    }

    /**
     * @param StoreCategoryRequest $request
     * @return RedirectResponse
     */
    public function store(StoreCategoryRequest $request): RedirectResponse
    {
        $record = $this->categoryService->create($request->toArray());

        return redirect()
            ->route('admin.categories.show', [
                'category' => $record->id,
                'type' => $request->integer('type'),
            ])
            ->with('success', 'Category created successfully.');
    }

    /**
     * @param Category $category
     * @param UpdateCategoryRequest $request
     * @return RedirectResponse
     */
    public function update(Category $category, UpdateCategoryRequest $request): RedirectResponse
    {
        $this->categoryService->update($request->validated(), $category->id);

        return redirect()
            ->route('admin.categories.show', [
                'category' => $category->id,
                'type' => $request->integer('type'),
            ])
            ->with('success', 'Category updated successfully.');
    }

    /**
     * @param Category $category
     * @return Response
     *
     * @throws ValidationException
     */
    public function show(Category $category): Response
    {
        $type = request()->integer('type');

        if (empty($type)) {
            throw ValidationException::withMessages([
                'message' => 'Category type is required.',
            ]);
        }

        $category->load('media');
        $categories = $this->categoryService->getTreeStructure($category->id, $type);
        $view = 'category/Show';

        if (EnumCategoryType::PRODUCT->is($type)) {
            $view = 'product-category/Show';
        }

        return Inertia::render($view, [
            'category' => $category->toArray(),
            'categories' => $categories,
            'type' => $type,
        ]);
    }

    /**
     * @param Category $category
     * @return RedirectResponse
     */
    public function destroy(Category $category): RedirectResponse
    {
        $type = request()->integer('type');
        $this->categoryService->delete(id: $category->id);

        return redirect()
            ->route('admin.categories.index', [
                'type' => $type,
            ])
            ->with('success', 'Category deleted successfully.');
    }

    public function multiDelete(): RedirectResponse
    {
        $type = request()->integer('type');
        $ids = request()->input('ids', []);
        $this->categoryService->multiDelete($ids);

        return redirect()
            ->route('admin.categories.index', [
                'type' => $type,
            ])
            ->with('success', 'Category deleted successfully.');
    }
}
