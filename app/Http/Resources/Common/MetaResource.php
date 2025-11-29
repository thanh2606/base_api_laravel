<?php

namespace App\Http\Resources\Common;

use Illuminate\Pagination\AbstractCursorPaginator;
use Illuminate\Pagination\AbstractPaginator;
use Symfony\Component\HttpFoundation\Response;

class MetaResource
{
    protected string $message;

    protected int $status;

    protected ?array $errors;

    protected mixed $resource;

    /**
     * MetaResource constructor.
     */
    public function __construct(
        mixed $resource = null,
        string $message = 'Successful operation',
        ?array $errors = null,
        int $code = Response::HTTP_OK
    ) {
        $this->message = $message;
        $this->errors = $errors;
        $this->status = $code;
        $this->resource = $resource;
    }

    public static function make(...$parameters): array
    {
        $self = new static(...$parameters);

        return $self->toArray();
    }

    public function toArray()
    {
        $response = [
            'message' => $this->message,
            'code' => $this->status,
        ];

        if ($this->errors) {
            $response = [
                'errors' => $this->errors,
            ];
        }

        if ($this->resource instanceof AbstractPaginator || $this->resource instanceof AbstractCursorPaginator) {
            return [
                'paginate' => [
                    'total' => $this->resource->total(),
                    'current_page' => $this->resource->currentPage(),
                    'per_page' => $this->resource->perPage(),
                    'last_page' => $this->resource->lastPage(),
                ],
                ...$response,
            ];
        }

        return $response;
    }
}
