<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Calculation;
use Illuminate\Http\JsonResponse;

class DestroyCalculationController extends Controller
{
    public function __invoke(int $calculation): JsonResponse
    {
        Calculation::findOrFail($calculation)->delete();

        return response()->json(null, 204);
    }
}
