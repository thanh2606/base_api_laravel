<?php

namespace App\Http\Controllers\Admin;

use App\Constants\ConstPaginate;
use App\Enums\EnumAttributePropertyType;
use App\Enums\EnumAttributeType;
use App\Enums\EnumCategoryType;
use App\Enums\EnumProductType;
use App\Enums\EnumTagType;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Product\StoreProductRequest;
use App\Http\Resources\Common\MetaResource;
use App\Models\Product;
use App\Services\Attribute\AttributeService;
use App\Services\Category\CategoryService;
use App\Services\Product\ProductService;
use App\Services\Tag\TagService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class ProductController extends Controller
{
    /**
     * @param ProductService $productService
     * @param CategoryService $categoryService
     * @param TagService $tagService
     * @param AttributeService $attributeService
     */
    public function __construct(
        protected readonly ProductService $productService,
        protected readonly CategoryService $categoryService,
        protected readonly TagService $tagService,
        protected readonly AttributeService $attributeService
    ) {}

    /**
     * Display a listing of the resource.
     * @param Request $request
     */
    public function index(Request $request): Response
    {
        $keyword = $request->query('search');
        $limit = $request->query('limit', ConstPaginate::PER_PAGE);
        $records = $this->productService->search($keyword, $limit);

        return Inertia::render('product/Index', [
            'records' => $records->items(),
            'meta' => MetaResource::make($records),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create(): Response
    {
        $categories = $this->categoryService->getTreeStructure(
            categoryType: EnumCategoryType::PRODUCT->value
        );

        $tags = $this->tagService->getTagByType(EnumTagType::PRODUCT->value);
        $productAttributes = $this->attributeService->getAttributeByAttributeType(EnumAttributePropertyType::PRODUCT);

        return Inertia::render('product/Create', [
            'categories' => $categories,
            'tags' => $tags,
            'productAttributes' => $productAttributes,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     * @param StoreProductRequest $request
     * @return RedirectResponse
     */
    public function store(StoreProductRequest $request): RedirectResponse
    {
        $type = $request->input('type');
        $attributes = $request->validated();

        try {
            switch ($type) {
                case EnumProductType::VIRTUAL->value:
                    $product = $this->productService->createVirtualProduct($attributes);
                    break;

                case EnumProductType::VARIABLE->value:
                    $product = $this->productService->createVariableProduct($attributes);
                    break;

                default:
                    $product = $this->productService->createSimpleProduct($attributes);
                    break;
            }

            return redirect()->route('admin.products.show', $product->id)->with('success', 'Product created successfully.');
        } catch (\Throwable $th) {
            return redirect()->back()->withInput()->withErrors(['errors' => __('messages.error.message')]);
        }
    }

    /**
     * Display the specified resource.
     * @param Product $product
     * @return Response
     */
    public function show(Product $product): Response
    {
        $product->load(['galleries', 'categories', 'tags']);
        $tags = $this->tagService->getTagByType(EnumTagType::PRODUCT->value);
        $categories = $this->categoryService->getTreeStructure(
            categoryType: EnumCategoryType::PRODUCT->value
        );

        $galleries = $product->galleries->map(fn ($model) => [
            'id' => $model->id,
            'name' => $model->name,
            'url' => $model->image,
        ])->toArray();

        return Inertia::render('product/Show', [
            'record' => $product,
            'categories' => $categories,
            'tags' => $tags,
            'selectCategories' => $product->categories->pluck('id')->toArray(),
            'selectTags' => $product->tags->pluck('id')->toArray(),
            'selectGalleries' => $product->galleries->pluck('id')->toArray(),
            'galleries' => $galleries ?? [],
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Product $product)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     * @param Product $product
     * @return RedirectResponse
     */
    public function destroy(Product $product): RedirectResponse
    {
        try {
            $this->productService->delete($product->id);

            return redirect()->route('admin.products.index')
                ->with('success', 'Product deleted successfully.');
        } catch (\Throwable $th) {
            return redirect()->back()->withErrors(['message' => __('messages.error.message')]);
        }
    }
}
