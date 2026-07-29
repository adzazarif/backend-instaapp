<?php

namespace App\Traits;

use Illuminate\Http\JsonResponse;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Http\Resources\Json\ResourceCollection;

trait ApiResponser
{
    /**
     * Return a success JSON response.
     *
     * @param  mixed  $data
     * @param  string|null  $message
     * @param  int  $code
     * @return JsonResponse
     */
    protected function success($data = null, string $message = null, int $code = 200): JsonResponse
    {
        return response()->json([
            'status' => 'success',
            'message' => $message,
            'data' => $data,
        ], $code);
    }

    /**
     * Return a success JSON response with pagination meta.
     * Can accept a LengthAwarePaginator or a ResourceCollection with pagination.
     *
     * @param  mixed  $data
     * @param  string|null  $message
     * @param  int  $code
     * @return JsonResponse
     */
    protected function successWithPagination($data, string $message = null, int $code = 200): JsonResponse
    {
        $meta = [];
        $items = $data;

        if ($data instanceof LengthAwarePaginator) {
            $meta = [
                'currentPage' => $data->currentPage(),
                'perPage' => $data->perPage(),
                'total' => $data->total(),
                'lastPage' => $data->lastPage(),
            ];
            $items = $data->items();
        } elseif (
            $data instanceof ResourceCollection &&
            $data->resource instanceof LengthAwarePaginator
        ) {
            $meta = [
                'currentPage' => $data->resource->currentPage(),
                'perPage' => $data->resource->perPage(),
                'total' => $data->resource->total(),
                'lastPage' => $data->resource->lastPage(),
            ];
            $items = $data->resolve();
        }

        return response()->json([
            'status' => 'success',
            'message' => $message,
            'data' => $items,
            'meta' => $meta,
        ], $code);
    }

    /**
     * Return an error JSON response.
     * Indicates whether it's a client error (4xx) or server error (5xx) via the HTTP status code.
     *
     * @param  string  $message
     * @param  int  $code
     * @param  mixed  $errors
     * @return JsonResponse
     */
    protected function error(string $message, int $code, $errors = null): JsonResponse
    {
        // 4xx = Client Error (Error di bagian user/request)
        // 5xx = Server Error (Error di bagian server)
        
        return response()->json([
            'status' => 'error',
            'message' => $message,
            'errors' => $errors,
        ], $code);
    }
}
