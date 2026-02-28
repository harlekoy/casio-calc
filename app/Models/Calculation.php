<?php

namespace App\Models;

use App\Casts\MathResult;
use App\Traits\BelongsToSession;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property string $session_id
 * @property string $expression
 * @property string $result
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 */
class Calculation extends Model
{
    use BelongsToSession;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'session_id',
        'expression',
        'result',
    ];

    /**
     * The attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'result' => MathResult::class,
        ];
    }
}
