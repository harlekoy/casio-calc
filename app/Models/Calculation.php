<?php

namespace App\Models;

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
    protected $fillable = [
        'session_id',
        'expression',
        'result',
    ];
}
