<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Calculation;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use MathParser\Interpreting\Evaluator;
use MathParser\StdMathParser;

class StoreCalculationController extends Controller
{
    public function __invoke(Request $request): JsonResponse
    {
        $request->validate([
            'expression' => 'required|string|max:500',
        ]);

        $sessionId = $request->header('X-Session-Id');

        if (! $sessionId) {
            return response()->json(['error' => 'Session ID required'], 400);
        }

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
                'session_id' => $sessionId,
                'expression' => $expression,
                'result' => $result,
            ]);

            return response()->json($calculation, 201);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Invalid expression'], 422);
        }
    }

    private function formatResult($result): string
    {
        if (floor($result) == $result && abs($result) < PHP_INT_MAX) {
            return (string) (int) $result;
        }

        return (string) round($result, 10);
    }
}
