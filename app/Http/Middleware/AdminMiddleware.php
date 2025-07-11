<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Example logic: check if user is admin
        if (!$request->user() || !$request->user()->is_admin) {
            return response()->json(['message' => 'Unauthorized. Admins only.'], 403);
        }
        return $next($request);
    }
}
