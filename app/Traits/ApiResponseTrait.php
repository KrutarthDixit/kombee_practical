<?php

namespace App\Traits;

use Symfony\Component\HttpFoundation\Response;

trait ApiResponseTrait
{
    /**
     * Function to return a success response
     */
    public function successResponse($data = null,string $message = 'Success', int $code = Response::HTTP_OK)
    {
        return response()->json([
            'status' => 'success',
            'message' => $message,
            'data' => $data,
        ], $code);
    }

    /**
     * Function to return an error response
     */
    public function errorResponse(string $message,int $code = Response::HTTP_BAD_REQUEST)
    {
        return response()->json([
            'status' => 'error',
            'message' => $message,
        ], $code);
    }
}
