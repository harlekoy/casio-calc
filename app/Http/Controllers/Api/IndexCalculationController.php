<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Calculation;
use Illuminate\Http\JsonResponse;

class IndexCalculationController extends Controller
{
    /**
     * List calculations
     *
     * Get all calculations for the current session, ordered by most recent first.
     *
     * @group Calculations
     *
     * @header X-Session-Id required The UUID identifying the browser session. Example: 550e8400-e29b-41d4-a716-446655440000
     *
     * @response 200 [{"id":1,"session_id":"550e8400-e29b-41d4-a716-446655440000","expression":"2+3","result":"5","created_at":"2026-03-01T12:00:00.000000Z","updated_at":"2026-03-01T12:00:00.000000Z"}]
     * @response 400 scenario="Missing session ID" {"error":"Session ID required"}
     */
    public function __invoke(): JsonResponse
    {
        $calculations = Calculation::orderBy('created_at', 'desc')->get();

        return response()->json($calculations);
    }
}
