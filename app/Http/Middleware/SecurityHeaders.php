<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SecurityHeaders
{
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        // Prevents browsers from MIME-sniffing the content type, forcing them to trust
        // the declared Content-Type — stops attackers from disguising scripts as other file types.
        $response->headers->set('X-Content-Type-Options', 'nosniff');

        // Blocks the page from being loaded inside an iframe, preventing clickjacking attacks
        // where an attacker overlays invisible frames to trick users into clicking hidden elements.
        $response->headers->set('X-Frame-Options', 'DENY');

        // Enables the browser's built-in XSS filter and tells it to block the page entirely
        // if a reflected cross-site scripting attack is detected (legacy browsers only).
        $response->headers->set('X-XSS-Protection', '1; mode=block');

        // Controls how much URL info is sent in the Referer header on navigation — sends the full
        // URL for same-origin requests but only the origin (no path) for cross-origin requests.
        $response->headers->set('Referrer-Policy', 'strict-origin-when-cross-origin');

        return $response;
    }
}
