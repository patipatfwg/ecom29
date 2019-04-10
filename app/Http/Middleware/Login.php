<?php
namespace App\Http\Middleware;

use Closure;
use Cookie;

class Login
{
    public function handle($request, Closure $next)
    {
        $messages = config('message');

    	if ($request->session()->has('userId')) {

            $encrypter = app('Illuminate\Encryption\Encrypter');
            $token = $encrypter->encrypt(Cookie::get($request->session()->getName()));
            // setcookie($request->session()->getName(), $token, time()+(config('session.lifetime')*60));


            return $next($request);
    	}

        $list = [
            'status' => false,
            'expired' => true
        ];
        if ($request->ajax())
        {
            return response()->json($list, 400);
        }

    	return redirect('/')->withErrors($messages['not_login']);
    }
}