<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCalculationRequest;
use App\Models\Calculation;
use Illuminate\Http\JsonResponse;
use MathParser\Interpreting\Evaluator;
use MathParser\StdMathParser;

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
     * @response 200 {"id":1,"session_id":"550e8400-e29b-41d4-a716-446655440000","expression":"sqrt(144)+5","result":"17","created_at":"2026-03-01T12:00:00.000000Z","updated_at":"2026-03-01T12:00:00.000000Z"}
     * @response 400 scenario="Missing session ID" {"error":"Session ID required"}
     * @response 403 scenario="Unauthorized" {"message":"This action is unauthorized."}
     * @response 422 scenario="Invalid expression" {"error":"Invalid expression"}
     * @response 422 scenario="Math error" {"error":"Math error"}
     */
    public function __invoke(StoreCalculationRequest $request): JsonResponse
    {
        $expression = $request->input('expression');

        try {
            $parser = new StdMathParser;
            $evaluator = new Evaluator;

            $ast = $parser->parse($expression);
            $result = $ast->accept($evaluator);

            if (is_infinite($result) || is_nan($result)) {
                return response()->json(['error' => 'Math error'], 422);
            }

            $result = $this->formatResult($result);

            $calculation = Calculation::create([
                'session_id' => $request->header('X-Session-Id'),
                'expression' => $expression,
                'result' => $result,
            ]);

            return response()->json($calculation);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Invalid expression'], 422);
        }
    }

    /**
     * Format the numeric result as a clean string.
     */
    private function formatResult($result): string
    {
        if (floor($result) == $result && abs($result) < PHP_INT_MAX) {
            return (string) (int) $result;
        }

        return (string) round($result, 10);
    }
}
