<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Calculation;
use Illuminate\Http\JsonResponse;

class IndexCalculationController extends Controller
{
    public function __invoke(): JsonResponse
    {
        $calculations = Calculation::orderBy('created_at', 'desc')->get();

        return response()->json($calculations);
    }
}
