<?php
namespace App\Http\Bootstrappers;

use Dotenv\Dotenv;
use InvalidArgumentException;
use Illuminate\Contracts\Foundation\Application;

class Environment {

    /**
    * Bootstrap the given application.
    *
    * @param  \Illuminate\Contracts\Foundation\Application  $app
    * @return void
    */
    public function bootstrap(Application $app)
    {
        $app->detectEnvironment(function(){

            if (getenv('ENVIRONMENT') == 'develop') {
                $environment = 'develop';
            } elseif (getenv('ENVIRONMENT') == 'alpha') {
                $environment = 'alpha';
            } elseif (getenv('ENVIRONMENT') == 'staging') {
                $environment = 'staging';
            } elseif (getenv('ENVIRONMENT') == 'production') {
                $environment = 'production';
            } elseif (getenv('ENVIRONMENT') == 'production01') {
                $environment = 'production01';
            } elseif (getenv('ENVIRONMENT') == 'production02') {
                $environment = 'production02';
            } elseif (getenv('ENVIRONMENT') == 'docker') {
                $environment = 'docker';
            } elseif (getenv('ENVIRONMENT') == 'stagingptvn') {
                $environment = 'stagingptvn';
            } elseif (getenv('ENVIRONMENT') == 'stagingptvn01') {
                $environment = 'stagingptvn01';
            } elseif (getenv('ENVIRONMENT') == 'stagingptvn02') {
                $environment = 'stagingptvn02';
            } else {
                $environment = 'production';
            }

            putenv("APP_ENV=$environment");

            if (getenv('APP_ENV')) {
                $dotenv = new Dotenv(base_path(), '.' . getenv('APP_ENV') . '.env');
                $dotenv->load();
            }

            return $environment;
        });
    }
}