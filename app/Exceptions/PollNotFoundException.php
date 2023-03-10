<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Http\JsonResponse;

class PollNotFoundException extends Exception
{
    /**
     * Render the exception into an HTTP response.
     *
     * @return JsonResponse
     */
    public function render(): JsonResponse
    {
        return response()->json([
            'message' => 'Poll not found'
        ], 404);
    }
}
