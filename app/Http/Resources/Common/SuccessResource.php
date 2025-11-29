<?php

namespace App\Http\Resources\Common;

use Illuminate\Http\Resources\Json\JsonResource;
use Symfony\Component\HttpFoundation\Response;

class SuccessResource extends JsonResource
{
    /**
     * Create a new resource instance.
     *
     * @param  mixed  $resource
     */
    public function __construct(
        mixed $resource,
        string $message = 'Successful operation',
        ?array $errors = null,
        int $code = Response::HTTP_OK)
    {
        parent::__construct($resource);
        $this->additional = (new MetaResource($resource, $message, $errors, $code))->toArray();
    }
}
