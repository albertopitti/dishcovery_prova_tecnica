<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use Tymon\JWTAuth\Http\Middleware\BaseMiddleware;

class AdminMiddleware extends BaseMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $this->auth->setToken(str_replace('Token ', '', $request->server('HTTP_AUTHORIZATION')));
        $user = $this->auth->authenticate();

        if($user->isAdmin())
            return $next($request);
        else
            return response('Forbidden', '403');
    }
}
