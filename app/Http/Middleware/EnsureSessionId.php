<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\Response;

/**
 * Validate the X-Session-Id header and register a Request macro
 * so the BelongsToSession trait can scope queries automatically.
 */
class EnsureSessionId
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $sessionId = $request->header('X-Session-Id');

        if (! $sessionId || ! Str::isUuid($sessionId)) {
            return response()->json(['error' => 'Session ID required'], Response::HTTP_BAD_REQUEST);
        }

        Request::macro('sessionId', fn () => $sessionId);

        return $next($request);
    }
}
