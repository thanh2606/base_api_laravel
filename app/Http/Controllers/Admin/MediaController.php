<?php

namespace App\Http\Controllers\Admin;

use App\Constants\ConstPaginate;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Media\SearchFileRequest;
use App\Http\Requests\Admin\Media\StoreFileRequest;
use App\Http\Requests\Admin\Media\TinyMceUploadFileRequest;
use App\Http\Resources\Common\MetaResource;
use App\Http\Resources\Media\MediaCollectionResource;
use App\Models\Media;
use App\Services\Media\MediaService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;
use Pion\Laravel\ChunkUpload\Exceptions\UploadFailedException;
use Pion\Laravel\ChunkUpload\Exceptions\UploadMissingFileException;

class MediaController extends Controller
{
    /**
     * @param MediaService $mediaService
     */
    public function __construct(
        private readonly MediaService $mediaService
    ) {}

    /**
     * @param SearchFileRequest $request
     * @return Response
     */
    public function index(SearchFileRequest $request): Response
    {
        $search = $request->query('search');
        $limit = $request->query('perPage', ConstPaginate::PER_PAGE);
        $records = $this->mediaService->search($search, $limit);

        return Inertia::render('media/Index', [
            'records' => MediaCollectionResource::make($records->items()),
            'meta' => MetaResource::make($records),
        ]);
    }

    /**
     * @param StoreFileRequest $request
     * @return JsonResponse
     *
     * @throws UploadMissingFileException|UploadFailedException
     */
    public function store(StoreFileRequest $request): JsonResponse
    {
        $data = $this->mediaService->uploadFile($request);

        return response()->json($data);
    }

    /**
     * @param Media $media
     * @return RedirectResponse
     */
    public function destroy(Media $media): RedirectResponse
    {
        $this->mediaService->deleteFile($media);

        return redirect()->back()->with('success', 'File deleted successfully');
    }

    /**
     * @param TinyMceUploadFileRequest $request
     * @return JsonResponse
     */
    public function tinyMceUpload(TinyMceUploadFileRequest $request): JsonResponse
    {
        $data = $this->mediaService->tinyMceUpload($request);

        return response()->json([
            'location' => $data['image'],
        ]);
    }

    /**
     * @return RedirectResponse
     */
    public function multiDelete(): RedirectResponse
    {
        $this->mediaService->multiDelete(request()->input('ids'));

        return redirect()->back()->with('success', 'Files deleted successfully');
    }
}
