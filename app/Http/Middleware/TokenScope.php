<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class TokenScope
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @param array $scopes
     * @return mixed
     */
    public function handle($request, Closure $next, ...$scopes)
    {
        if (Auth::check()) {
            foreach ($scopes as $scope) {
                if ($request->user()->tokenCan($scope)) {
                    return $next($request);
                }
            }
        }

        return response()->json([
            'message' => 'forbidden'
        ], 403);
    }
}
