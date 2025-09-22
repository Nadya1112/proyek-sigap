<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class NoCache
{
    public function handle(Request $request, Closure $next): Response
    {
        /** @var Response $response */
        $response = $next($request);

        // Set header tanpa method chaining (compatible dg Symfony Response)
        $response->headers->set('Cache-Control', 'no-store, no-cache, must-revalidate, max-age=0, private');
        $response->headers->set('Pragma', 'no-cache');
        $response->headers->set('Expires', 'Fri, 01 Jan 1990 00:00:00 GMT');
        // (opsional) untuk proxy/CDN
        $response->headers->set('Surrogate-Control', 'no-store');

        return $response;
    }
}
