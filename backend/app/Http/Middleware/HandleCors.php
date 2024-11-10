<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class HandleCors
{
    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param Closure $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);

        // Set CORS headers to allow requests from specific origin
        $response->headers->set('Access-Control-Allow-Origin', 'https://cupcake-store-frontend-fc44c717c483.herokuapp.com');

        // Allow specific methods for cross-origin requests
        $response->headers->set('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, OPTIONS');

        // Allow headers that may be sent with requests
        $response->headers->set('Access-Control-Allow-Headers', 'Content-Type, Authorization');

        // Allow credentials to be included in requests if needed
        $response->headers->set('Access-Control-Allow-Credentials', 'true');

        // Handle preflight (OPTIONS) requests
        if ($request->getMethod() === 'OPTIONS') {
            return response('', 200)
                ->header('Access-Control-Allow-Origin', 'https://cupcake-store-frontend-fc44c717c483.herokuapp.com')
                ->header('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, OPTIONS')
                ->header('Access-Control-Allow-Headers', 'Content-Type, Authorization')
                ->header('Access-Control-Allow-Credentials', 'true');
        }

        return $response;
    }
}
