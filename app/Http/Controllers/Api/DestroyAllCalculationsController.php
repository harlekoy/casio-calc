<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Calculation;
use Illuminate\Http\Response;

class DestroyAllCalculationsController extends Controller
{
    /**
     * Clear all calculations
     *
     * Delete all calculations for the current session.
     *
     * @group Calculations
     *
     * @header X-Session-Id required The UUID identifying the browser session. Example: 550e8400-e29b-41d4-a716-446655440000
     *
     * @response 204 scenario="Success" ""
     * @response 400 scenario="Missing session ID" {"error":"Session ID required"}
     */
    public function __invoke(): Response
    {
        Calculation::query()->delete();

        return response()->noContent();
    }
}
