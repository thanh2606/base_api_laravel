<?php

namespace App\Traits;

use App\Models\Media;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\File\UploadedFile;

trait UploadTrait
{
    /**
     * Uploads a file.
     *
     * @return array The uploaded file information.
     */
    public function upload(UploadedFile $uploadedFile, string $path = ''): array
    {
        $date = Carbon::now()->format('Y-m-d');
        $file = $this->getFileInfo($uploadedFile);

        if (! $path) {
            $path = 'images/'.$date.'/';
        }

        $filePath = $path.$file['name'];
        Storage::put($filePath, $uploadedFile->getContent());

        $media = Media::query()->create([
            'name' => $file['name'],
            'path' => $filePath,
            'width' => $file['width'],
            'height' => $file['height'],
            'mime_type' => $file['type'],
        ]);

        return [
            'id' => $media->id,
            'mime_type' => $media->type,
            'name' => $media->name,
            'image' => $media->image,
            'width' => $media->width,
            'height' => $media->height,
        ];
    }

    /**
     * Returns an array of information about an uploaded file.
     *
     * The returned array has the following keys:
     * - width: The width of the image in pixels, or null if the image is not an image.
     * - height: The height of the image in pixels, or null if the image is not an image.
     * - name: The name of the file as provided by the client.
     * - path: The path to the file on the server.
     * - type: The MIME type of the file.
     */
    public function getFileInfo(UploadedFile $uploadedFile): array
    {
        $pathName = $uploadedFile->getPathname();
        $name = $uploadedFile->getClientOriginalName();
        $size = getimagesize($uploadedFile->getPathname());
        $type = $uploadedFile->getMimeType();

        return [
            'width' => $size[0] ?? null,
            'height' => $size[1] ?? null,
            'name' => $name,
            'path' => $pathName,
            'type' => $type,
        ];
    }

    /**
     * Deletes a file from the server by its path.
     *
     * If the file does not exist, this method does nothing.
     *
     * @param  string|null  $path  The path of the file to delete.
     */
    public function deleteByPath(?string $path = null): void
    {
        if (!$path || ! Storage::fileExists($path)) {
            return;
        }

        Storage::delete($path);
    }
}
