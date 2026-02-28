<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Http\JsonResponse;

/**
 * Thrown when a math expression cannot be parsed or evaluated.
 */
class InvalidExpressionException extends Exception
{
    /**
     * Render the exception as an HTTP response.
     */
    public function render(): JsonResponse
    {
        return response()->json(['error' => 'Invalid expression'], 422);
    }
}
