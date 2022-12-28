<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Http\JsonResponse;

class CannotVoteTwiceException extends Exception
{
    /**
     * Render the exception into an HTTP response.
     *
     * @return JsonResponse
     */
    public function render(): JsonResponse
    {
        return response()->json([
            'message' => 'User already voted on this poll'
        ], 403);
    }
}
