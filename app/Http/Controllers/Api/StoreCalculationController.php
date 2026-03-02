<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCalculationRequest;
use App\Http\Resources\CalculationResource;
use App\Models\Calculation;

class StoreCalculationController extends Controller
{
    /**
     * Create calculation
     *
     * Evaluate a math expression and store the result. Supports basic arithmetic,
     * trigonometric functions (sin, cos, tan, asin, acos, atan), logarithms (ln, log10),
     * exponents (exp, ^), square root (sqrt), factorial (!), absolute value (abs),
     * rounding (floor, ceil), and constants (pi, e).
     *
     * @group Calculations
     *
     * @header X-Session-Id required The UUID identifying the browser session. Example: 550e8400-e29b-41d4-a716-446655440000
     *
     * @bodyParam expression string required The math expression to evaluate. Example: sqrt(144)+5
     *
     * @response 201 {"data":{"id":1,"session_id":"550e8400-e29b-41d4-a716-446655440000","expression":"sqrt(144)+5","result":"17","created_at":"2026-03-01T12:00:00.000000Z","updated_at":"2026-03-01T12:00:00.000000Z"}}
     * @response 400 scenario="Missing session ID" {"error":"Session ID required"}
     * @response 403 scenario="Unauthorized" {"message":"This action is unauthorized."}
     * @response 422 scenario="Validation error" {"message":"Invalid expression","errors":{"expression":["Invalid expression"]}}
     */
    public function __invoke(StoreCalculationRequest $request): CalculationResource
    {
        $calculation = Calculation::create([
            'expression' => $request->input('expression'),
            'result' => $request->evaluatedResult(),
        ]);

        return CalculationResource::make($calculation);
    }
}
