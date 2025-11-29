<?php

namespace App\Services\Media;

use App\Http\Requests\Admin\Media\StoreFileRequest;
use App\Http\Requests\Admin\Media\TinyMceUploadFileRequest;
use App\Models\Media;
use App\Repositories\Media\MediaRepository;
use App\Services\BaseService;
use App\Traits\UploadTrait;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Pion\Laravel\ChunkUpload\Exceptions\UploadFailedException;
use Pion\Laravel\ChunkUpload\Exceptions\UploadMissingFileException;
use Pion\Laravel\ChunkUpload\Handler\HandlerFactory;
use Pion\Laravel\ChunkUpload\Receiver\FileReceiver;

class MediaService extends BaseService implements MediaServiceInterface
{
    use UploadTrait;

    /**
     * @param MediaRepository $mediaRepository
     */
    public function __construct(
        private readonly MediaRepository $mediaRepository
    ) {
        $this->repository = $mediaRepository;
    }

    /**
     * @param TinyMceUploadFileRequest $request
     * @return array
     */
    public function tinyMceUpload(TinyMceUploadFileRequest $request): array
    {
        $file = $request->file('file');

        return $this->upload($file);
    }

    /**
     * @param StoreFileRequest $request
     * @return array|array{progress: int}
     *
     * @throws UploadMissingFileException|UploadFailedException
     */
    public function uploadFile(StoreFileRequest $request): array
    {
        $fileReceiver = new FileReceiver('file', $request, HandlerFactory::classFromRequest($request));

        if (!$fileReceiver->isUploaded()) {
            throw new UploadMissingFileException();
        }

        $receive = $fileReceiver->receive();

        if ($receive->isFinished()) {
            return $this->upload($receive->getFile());
        }

        $handle = $receive->handler();

        return [
            'progress' => $handle->getPercentageDone(),
        ];
    }

    /**
     * @param Media $media
     * @return void
     */
    public function deleteFile(Media $media): void
    {
        $this->deleteByPath($media->path);
        $media->delete();
    }

    /**
     * @param array $ids
     * @return void
     */
    public function multiDelete(array $ids): void
    {
        $medias = $this->repository->getByIds($ids);

        foreach ($medias as $media) {
            $this->deleteByPath($media->path);
            $media->delete();
        }
    }

    /**
     * @param string|null $search
     * @param int|null $limit
     * @return LengthAwarePaginator
     */
    public function search(?string $search = null, ?int $limit = null): LengthAwarePaginator
    {
        if (empty($search)) {
            return $this->repository->paginate($limit);
        }

        return $this->repository->scopeQuery(function ($query) use ($search) {
            return $query->where('name', 'like', '%'.$search.'%');
        })->paginate($limit);
    }
}
