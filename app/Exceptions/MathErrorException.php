<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Http\JsonResponse;

/**
 * Thrown when a math operation produces an infinite or NaN result.
 */
class MathErrorException extends Exception
{
    /**
     * Render the exception as an HTTP response.
     */
    public function render(): JsonResponse
    {
        return response()->json(['error' => 'Math error'], 422);
    }
}
