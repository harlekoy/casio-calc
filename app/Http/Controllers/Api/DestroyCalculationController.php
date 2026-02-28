<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Calculation;
use Illuminate\Http\Response;

class DestroyCalculationController extends Controller
{
    public function __invoke(int $calculation): Response
    {
        Calculation::findOrFail($calculation)->delete();

        return response()->noContent();
    }
}
