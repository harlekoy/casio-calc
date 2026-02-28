<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureSessionId
{
    public function handle(Request $request, Closure $next): Response
    {
        if (! $request->header('X-Session-Id')) {
            return response()->json(['error' => 'Session ID required'], 400);
        }

        return $next($request);
    }
}
