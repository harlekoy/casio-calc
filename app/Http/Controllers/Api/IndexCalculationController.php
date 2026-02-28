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
        $calculations = Calculation::where('session_id', $request->header('X-Session-Id'))
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json($calculations);
    }
}
