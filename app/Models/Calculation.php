<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class Calculation extends Model
{
    use HasUuids;
    protected $fillable = [
        'session_id',
        'expression',
        'result',
    ];
}
