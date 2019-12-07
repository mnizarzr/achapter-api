<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class JWTAuthAdmin
{

    public function handle($request, Closure $next)
    {
        if(Auth::user()['role'] !== 'admin'){
            return response()->json([
                "status" => 401,
                "error" => "ERR_UNAUTHORIZED_REQUEST",
                "message" => "Unauthorized"
            ], 401);
        }
        
        return $next($request);
    }

}
