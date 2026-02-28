<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Calculation;
use Illuminate\Http\Response;

class DestroyAllCalculationsController extends Controller
{
    public function __invoke(): Response
    {
        Calculation::query()->delete();

        return response()->noContent();
    }
}
