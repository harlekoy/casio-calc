<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Calculation;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class IndexCalculationController extends Controller
{
    public function __invoke(Request $request): JsonResponse
    {
        $sessionId = $request->header('X-Session-Id');

        if (! $sessionId) {
            return response()->json(['error' => 'Session ID required'], 400);
        }

        $calculations = Calculation::where('session_id', $sessionId)
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json($calculations);
    }
}
