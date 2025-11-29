<?php

namespace App\Http\Controllers\Admin;

use App\Constants\ConstPaginate;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Attribute\SearchAttributeRequest;
use App\Http\Requests\Admin\ProductAttribute\StoreAttributeRequest;
use App\Http\Requests\Admin\ProductAttribute\UpdateAttributeRequest;
use App\Http\Resources\Common\MetaResource;
use App\Models\Attribute;
use App\Services\Attribute\AttributeService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;
use Inertia\Response;

class AttributeController extends Controller
{
    /**
     * @param AttributeService $productAttributeService
     */
    public function __construct(
        protected readonly AttributeService $productAttributeService
    ) {}

    /**
     * @param SearchAttributeRequest $request
     * @return Response
     */
    public function index(SearchAttributeRequest $request): Response
    {
        $keyword = $request->input('search');
        $limit = request()->query('perPage', ConstPaginate::PER_PAGE);
        $records = $this->productAttributeService->search($keyword, $limit);

        return Inertia::render('attribute/Index', [
            'records' => $records->items(),
            'meta' => MetaResource::make($records),
        ]);
    }

    /**
     * @param Attribute $attribute
     * @return Response
     */
    public function show(Attribute $attribute): Response
    {
        $attribute->load(['values']);

        return Inertia::render('attribute/Show', [
           'attribute' => $attribute,
        ]);
    }

    /**
     * @return Response
     */
    public function create(): Response
    {
        return Inertia::render('attribute/Create');
    }

    /**
     * @param StoreAttributeRequest $request
     * @return RedirectResponse
     */
    public function store(StoreAttributeRequest $request): RedirectResponse
    {
        $record = $this->productAttributeService->create($request->validated());

        return redirect()->route('admin.attributes.show', $record->id)
            ->with('success', 'Thuộc tính đã được tạo thành công.');
    }

    /**
     * Update an existing product attribute.
     *
     * @param Attribute $attribute
     * @param UpdateAttributeRequest $request
     * @return RedirectResponse
     */
    public function update(Attribute $attribute, UpdateAttributeRequest $request): RedirectResponse
    {
        $this->productAttributeService->update($request->validated(), $attribute->id);

        return redirect()->route('admin.attributes.show', $attribute->id)
            ->with('success', 'Thuộc tính đã được cập nhật thành công.');
    }

    /**
     * Delete an existing product attribute.
     *
     * @param Attribute $attribute
     * @return RedirectResponse
     */
    public function destroy(Attribute $attribute): RedirectResponse
    {
        $attribute->load('values');

        DB::transaction(function () use ($attribute) {
            $attribute->values()->each(function ($value) {
                $value->delete();
            });
            $this->productAttributeService->delete($attribute->id);
        });

        return redirect()->route('admin.attributes.index')
            ->with('success', 'Thuộc tính đã được xóa thành công.');
    }
}
