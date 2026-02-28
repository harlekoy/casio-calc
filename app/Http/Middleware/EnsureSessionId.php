<?php

namespace App\Http\Middleware;

use App\Models\Calculation;
use Closure;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureSessionId
{
    public function handle(Request $request, Closure $next): Response
    {
        if (! $request->header('X-Session-Id')) {
            return response()->json(['error' => 'Session ID required'], 400);
        }

        Calculation::addGlobalScope('session', function (Builder $query) use ($request) {
            $query->where('session_id', $request->header('X-Session-Id'));
        });

        return $next($request);
    }
}
