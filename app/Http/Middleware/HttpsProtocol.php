<?php
namespace App\Http\Middleware;

use Closure;

class HttpsProtocol {

    public function handle($request, Closure $next)
    {
        if (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] == 'http' && in_array(env('APP_ENV'), ['staging', 'production', 'stagingptvn']))
        {
            return redirect()->secure($request->getRequestUri());
        }

        return $next($request);
    }
}