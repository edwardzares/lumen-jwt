<?php
/**
 * Created by PhpStorm.
 * User: cristhian
 * Date: 4/7/17
 * Time: 7:14 AM
 */

namespace App\Http\Middleware;

use Closure;
use Exception;
use Tymon\JWTAuth\Facades\JWTAuth;


class ParseMiddleware
{

    public function handle($request, Closure $next, $guard = null)
    {
        $payload = null;
        try{
            $payload = JWTAuth::parseToken()->getPayload();
        }catch(Exception $e){
            //Without auth
        }
        $request->attributes->add([
            'payload' => $payload
        ]);
        return $next($request);
    }

}