<?php

namespace App\Traits;

use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

trait ApiResponse
{
    /**
     * Return a JSON response
     *
     * @param array $data
     * @param integer $code
     * @return JsonResponse
     */
    public function response(array $data, int $code): JsonResponse
    {
        return response()->json(['code' => $code, ...$data], $code);
    }

    /**
     * Return a unauthorized JSON response with the data
     *
     * @return JsonResponse
     */
    public function unauthorizedResponse(): JsonResponse
    {
        return response()->json(['status' => false, 'message' => 'unauthorized'], Response::HTTP_UNAUTHORIZED);
    }

    /**
     * Return a success JSON response with the data
     *
     * @param array $data
     * @param string $message
     * @return JsonResponse
     */
    public function successResponse(array $data = [], string $message = ''): JsonResponse
    {
        return response()->json(['status' => true, 'message' => $message, ...$data]);
    }

    /**
     * Return a error JSON response with the message
     *
     * @param string $message
     * @param [type] $code
     * @return JsonResponse
     */
    public function errorResponse($message = 'Error happened.', $code = Response::HTTP_UNPROCESSABLE_ENTITY): JsonResponse
    {
        return response()->json(['status' => false, 'message' => $message], $code);

    }

    /**
     * Return a validation error JSON response with the errors
     *
     * @param [type] $errors
     * @return JsonResponse
     */
    public function validationErrorResponse($errors): JsonResponse
    {
        return response()->json(['status' => false, 'message' => $errors], Response::HTTP_UNPROCESSABLE_ENTITY);

    }

    /**
     * Return a not found JSON response with the message
     *
     * @param string $message
     * @return JsonResponse
     */
    public function notFoundErrorResponse($message = 'Not Found'): JsonResponse
    {
        return response()->json(['status' => false, 'message' => $message], Response::HTTP_NOT_FOUND);
    }
}
