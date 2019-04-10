<?php namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class ValidatorServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->app['validator']->extend('emaildomain', function ($attribute, $value, $parameters)
        {
            if (filter_var($value, FILTER_VALIDATE_EMAIL)) {
                $domain = explode("@", $value, 2);
                if(checkdnsrr($domain[1])){
                    return true;
                }
            }

            return false;
        });
    }
}