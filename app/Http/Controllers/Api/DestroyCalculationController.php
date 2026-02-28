<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Calculation;
use Illuminate\Http\Response;

class DestroyCalculationController extends Controller
{
    /**
     * Delete calculation
     *
     * Delete a single calculation by ID. Only calculations belonging to the current session can be deleted.
     *
     * @group Calculations
     *
     * @header X-Session-Id required The UUID identifying the browser session. Example: 550e8400-e29b-41d4-a716-446655440000
     *
     * @urlParam calculation int required The calculation ID. Example: 1
     *
     * @response 204 scenario="Success" ""
     * @response 400 scenario="Missing session ID" {"error":"Session ID required"}
     * @response 404 scenario="Not found" {"message":"No query results for model [App\\Models\\Calculation] 999"}
     */
    public function __invoke(int $calculation): Response
    {
        Calculation::findOrFail($calculation)->delete();

        return response()->noContent();
    }
}
