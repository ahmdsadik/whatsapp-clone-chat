<?php

namespace App\Traits;

use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

trait ApiResponse
{
    public function response(array $data, int $code): JsonResponse
    {
        return response()->json(['code' => $code, ...$data], $code);
    }

    public function unauthorizedResponse(): JsonResponse
    {
        return response()->json(['status' => false, 'message' => 'unauthorized'], Response::HTTP_UNAUTHORIZED);
    }

    public function successResponse(array $data = [], string $message = ''): JsonResponse
    {
        return response()->json(['status' => true, 'message' => $message, ...$data]);
    }

    public function errorResponse($message = 'Error happened.', $code = Response::HTTP_UNPROCESSABLE_ENTITY): JsonResponse
    {
        return response()->json(['status' => false, 'message' => $message], $code);

    }

    public function validationErrorResponse($errors): JsonResponse
    {
        return response()->json(['status' => false, 'message' => $errors], Response::HTTP_UNPROCESSABLE_ENTITY);

    }

    public function notFoundErrorResponse($message = 'Not Found'): JsonResponse
    {
        return response()->json(['status' => false, 'message' => $message], Response::HTTP_NOT_FOUND);
    }
}
