<?php
namespace App\Http\Middleware;

use Closure;

class Language
{
    public function handle($request, Closure $next)
    {
    	if ($request->session()->has('locale')) {
    		app()->setLocale($request->session()->get('locale'));
    	}

        return $next($request);
    }
}