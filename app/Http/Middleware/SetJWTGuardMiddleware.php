<?php

namespace App\Http\Middleware;
use \Closure;

// 注意，我们要继承的是 jwt 的 BaseMiddleware

class SetJWTGuardMiddleware
{
    /**
     * @param $request
     * @param Closure $next
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\Response|mixed
     */
    public function handle($request, Closure $next, $guard = null)
    {
        config(['auth.defaults.guard' => $guard]);
        return $next($request);
    }

}
