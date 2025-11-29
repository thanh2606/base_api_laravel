<?php

namespace App\Services\Media;

use App\Http\Requests\Admin\Media\StoreFileRequest;
use App\Models\Media;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Pion\Laravel\ChunkUpload\Exceptions\UploadMissingFileException;

interface MediaServiceInterface
{
    /**
     * @param StoreFileRequest $request
     * @return array|array{progress: int}
     *
     * @throws UploadMissingFileException
     */
    public function uploadFile(StoreFileRequest $request): array;

    /**
     * @param Media $media
     * @return void
     */
    public function deleteFile(Media $media): void;

    /**
     * @param string|null $search
     * @param int|null $limit
     * @return LengthAwarePaginator
     */
    public function search(?string $search = null, ?int $limit = null): LengthAwarePaginator;
}
