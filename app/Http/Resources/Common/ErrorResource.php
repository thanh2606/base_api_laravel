<?php

namespace App\Http\Resources\Common;

use Illuminate\Http\JsonResponse;
use JsonSerializable;
use Symfony\Component\HttpFoundation\Response;

class ErrorResource implements JsonSerializable
{
    public $resource;

    public string $message;

    public ?array $errors;

    public int $status;

    public function __construct(
        mixed $resource = null,
        string $message = 'Has error',
        ?array $errors = null,
        int $code = Response::HTTP_BAD_REQUEST
    ) {
        $this->resource = $resource;
        $this->message = $message;
        $this->errors = $errors;
        $this->status = $code;
    }

    public function jsonSerialize(): array
    {
        return [
            'data' => null,
            'meta' => (new MetaResource(
                resource: $this->resource,
                message: $this->message,
                errors: $this->errors,
                code: $this->status,
            ))->toArray(),
        ];
    }

    public function toResponse(): JsonResponse
    {
        return response()->json($this, $this->status);
    }
}
