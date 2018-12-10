<?php

namespace App\Http\Middleware;

use Closure;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Http\Middleware\BaseMiddleware;

class JWTAuthMiddleware extends BaseMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        try {
            if (!$user = $this->auth->parseToken()->authenticate()) {
                return response()->json(['message' => 'User not found'], 401);
            }
            return $next($request);
        } catch (TokenExpiredException $e) {
            try {
                $refreshedToken = $this->auth->refresh();
                $this->auth->setToken($refreshedToken)->toUser();
                $response = $next($request);
                $this->setAuthenticationHeader($response, $refreshedToken);
                return $response;
            } catch (JWTException $e) {
                return response()->json(['message' => $e->getMessage()], 401);
            }
        } catch (JWTException $e) {
            return response()->json(['message' => $e->getMessage()], 401);
        }
    }
}
