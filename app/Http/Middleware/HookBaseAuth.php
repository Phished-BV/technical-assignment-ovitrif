<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class HookBaseAuth
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Simplest form of basic auth to protect the webhook endpoint.
        if (!config('baseauth.users')->contains([$request->getUser(), $request->getPassword()])) {
            $headers = ['WWW-Authenticate' => 'Basic'];
            return response('Invalid credentials.', 401, $headers);
        }

        return $next($request);
    }
}
