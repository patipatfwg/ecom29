<?php
namespace App\Http\Middleware;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;
use Closure;
use Route;

class Logging {

	public function handle($request, Closure $next)
	{
		$username = $request->session()->get('userName');
		$route = Route::getCurrentRoute()->getName();

        // Logging
        $method = $request->method();
        $url = $request->fullUrl();
        $input = $request->input();

        if(isset($input['password'])) {
            $input['password'] = Hash::make($input['password']);
        }

        if(isset($input['password_confirmation'])) {
            $input['password_confirmation'] = Hash::make($input['password_confirmation']);
        }
        
        Log::info("User(". $username .") request(".$method.") to " . $url, $input);

        return $next($request);
	}
}