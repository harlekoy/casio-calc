<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Calculation;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class DestroyAllCalculationsController extends Controller
{
    public function __invoke(Request $request): JsonResponse
    {
        $sessionId = $request->header('X-Session-Id');

        if (! $sessionId) {
            return response()->json(['error' => 'Session ID required'], 400);
        }

        Calculation::where('session_id', $sessionId)->delete();

        return response()->json(null, 204);
    }
}
