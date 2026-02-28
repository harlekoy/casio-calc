<?php

namespace App\Http\Requests;

use App\Rules\ValidExpression;
use Illuminate\Foundation\Http\FormRequest;

class StoreCalculationRequest extends FormRequest
{
    /**
     * The expression validation rule instance.
     */
    private ValidExpression $expressionRule;

    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return (bool) $this->header('X-Session-Id');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $this->expressionRule = new ValidExpression;

        return [
            'expression' => ['required', 'string', 'max:500', $this->expressionRule],
        ];
    }

    /**
     * Get the evaluated result from the expression rule.
     */
    public function evaluatedResult(): float
    {
        return $this->expressionRule->getResult();
    }
}
