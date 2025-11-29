<?php

namespace App\Http\Controllers\Admin;

use App\Constants\ConstPaginate;
use App\Enums\EnumTagType;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Tag\SearchTagRequest;
use App\Http\Requests\Admin\Tag\StoreTagRequest;
use App\Http\Requests\Admin\Tag\UpdateTagRequest;
use App\Http\Resources\Common\MetaResource;
use App\Models\Tag;
use App\Services\Tag\TagService;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;

class TagController extends Controller
{
    /**
     * @param TagService $tagService
     */
    public function __construct(
        private readonly TagService $tagService
    ) {}

    public function index(SearchTagRequest $request): Response
    {
        $keyword = $request->input('search');
        $limit = $request->input('perPage', ConstPaginate::PER_PAGE);
        $type = $request->integer('type', EnumTagType::POST->value);
        $records = $this->tagService->search($type, $keyword, $limit);
        $view = 'tag/Index';

        if (EnumTagType::PRODUCT->is($type)) {
            $view = 'product-tag/Index';
        }

        return Inertia::render($view, [
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
        $view = 'tag/Create';

        if (EnumTagType::PRODUCT->is($type)) {
            $view = 'product-tag/Create';
        }

        return Inertia::render($view, [
            'type' => $type,
        ]);
    }

    /**
     * @param StoreTagRequest $request
     * @return RedirectResponse
     */
    public function store(StoreTagRequest $request): RedirectResponse
    {
        $tag = $this->tagService->create($request->validated());
        return redirect()->route('admin.tags.show', [
            'tag' => $tag->id,
            'type' => $request->integer('type')
        ])->with('success', 'Tạo thẻ tag thành công');
    }

    /**
     * @param Tag $tag
     * @return Response
     */
    public function show(Tag $tag): Response
    {
        $type = request()->integer('type');
        $view = 'tag/Show';

        if (EnumTagType::PRODUCT->is($type)) {
            $view = 'product-tag/Show';
        }

        return Inertia::render($view, [
            'record' => $tag,
            'type' => $type,
        ]);
    }

    /**
     * @param Tag $tag
     * @param UpdateTagRequest $tagRequest
     * @return RedirectResponse
     */
    public function update(Tag $tag, UpdateTagRequest $tagRequest): RedirectResponse
    {
        $tag = $this->tagService->update($tagRequest->validated(), $tag->id);
        return redirect()
            ->route('admin.tags.show', [
                'tag' => $tag->id,
                'type' => $tagRequest->integer('type')
            ])
            ->with('success', 'Cập nhật thẻ tag thành công');
    }

    /**
     * @param Tag $tag
     * @return RedirectResponse
     */
    public function destroy(Tag $tag): RedirectResponse
    {
        $tag->delete();
        $type = request()->integer('type');

        return redirect()
            ->route('admin.tags.index', ['type' => $type])
            ->with('success', 'Xóa thẻ tag thành công');
    }
}
