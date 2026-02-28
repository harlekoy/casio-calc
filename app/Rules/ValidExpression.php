<?php

namespace App\Rules;

use Closure;
use Exception;
use Illuminate\Contracts\Validation\ValidationRule;
use MathParser\Interpreting\Evaluator;
use MathParser\StdMathParser;

/**
 * Validate that a math expression can be parsed and evaluated.
 */
class ValidExpression implements ValidationRule
{
    /**
     * The evaluated result of the expression.
     */
    protected float $result;

    /**
     * Run the validation rule.
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        try {
            $parser = new StdMathParser;
            $evaluator = new Evaluator;

            $ast = $parser->parse($value);
            $this->result = $ast->accept($evaluator);
        } catch (Exception $e) {
            $fail('Invalid expression');

            return;
        }

        if (is_infinite($this->result) || is_nan($this->result)) {
            $fail('Math error');
        }
    }

    /**
     * Get the evaluated result.
     */
    public function getResult(): float
    {
        return $this->result;
    }
}
