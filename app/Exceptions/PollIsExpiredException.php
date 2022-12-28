<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Http\JsonResponse;

class PollIsExpiredException extends Exception
{
    /**
     * Render the exception into an HTTP response.
     *
     * @return JsonResponse
     */
    public function render(): JsonResponse
    {
        return response()->json([
            'message' => 'Poll is expired'
        ], 403);
    }
}
