<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Calculation;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class DestroyCalculationController extends Controller
{
    public function __invoke(Request $request, Calculation $calculation): JsonResponse
    {
        $sessionId = $request->header('X-Session-Id');

        if ($calculation->session_id !== $sessionId) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $calculation->delete();

        return response()->json(null, 204);
    }
}
