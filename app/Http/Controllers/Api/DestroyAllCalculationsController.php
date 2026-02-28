<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Calculation;
use Illuminate\Http\JsonResponse;

class DestroyAllCalculationsController extends Controller
{
    public function __invoke(): JsonResponse
    {
        Calculation::query()->delete();

        return response()->json(null, 204);
    }
}
