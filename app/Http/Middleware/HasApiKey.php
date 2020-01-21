<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\DB;

class HasApiKey
{

    public function handle($request, Closure $next)
    {

        $api_key = $request->header('api-key');

        if($api_key != null){
            if(DB::table('tbl_api_key')->where('consumer_key', $api_key)->first() != null){
                return $next($request);
            }
            return response()->json([
                "status" => 401,
                "error" => "ERR_UNAUTHORIZED_REQUEST",
                "message" => "Unauthorized"
            ], 401);    
        }

        return response()->json([
            "status" => 401,
            "error" => "ERR_UNAUTHORIZED_REQUEST",
            "message" => "Unauthorized"
        ], 401);
    }

}
