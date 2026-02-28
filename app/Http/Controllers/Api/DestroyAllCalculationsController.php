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
        Calculation::where('session_id', $request->header('X-Session-Id'))->delete();

        return response()->json(null, 204);
    }
}
