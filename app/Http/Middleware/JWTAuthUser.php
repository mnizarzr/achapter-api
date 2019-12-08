<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class JWTAuthUser
{

    public function handle($request, Closure $next)
    {
        if (!Auth::user()['id']) {
            return response()->json([
                "status" => 401,
                "error" => "ERR_UNAUTHORIZED_REQUEST",
                "message" => "Unauthorized"
            ], 401);
        }

        return $next($request);
    }
}
