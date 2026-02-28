<?php

namespace App\Casts;

use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Illuminate\Database\Eloquent\Model;

/**
 * Cast a numeric result to a clean string representation.
 * Integers are returned without decimals, floats are rounded to 10 places.
 */
class MathResult implements CastsAttributes
{
    /**
     * Cast the given value.
     */
    public function get(Model $model, string $key, mixed $value, array $attributes): string
    {
        return (string) $value;
    }

    /**
     * Prepare the given value for storage.
     */
    public function set(Model $model, string $key, mixed $value, array $attributes): string
    {
        $value = (float) $value;

        if (floor($value) == $value && abs($value) < PHP_INT_MAX) {
            return (string) (int) $value;
        }

        return (string) round($value, 10);
    }
}
