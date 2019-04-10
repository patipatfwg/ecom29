<?php

namespace App\Http;

use Illuminate\Contracts\Foundation\Application;
use Illuminate\Routing\Router;
use Illuminate\Foundation\Http\Kernel as HttpKernel;

class Kernel extends HttpKernel
{   
    public function __construct(Application $app, Router $router)
    {
        parent::__construct($app, $router);

        array_walk($this->bootstrappers, function(&$bootstrapper)
        {
            if($bootstrapper === 'Illuminate\Foundation\Bootstrap\ConfigureLogging')
            {
                $bootstrapper = 'App\Http\Bootstrappers\ConfigureLogging';
            }
        });
    }

    /**
     * The application's global HTTP middleware stack.
     *
     * These middleware are run during every request to your application.
     *
     * @var array
     */
    protected $middleware = [
        \Illuminate\Foundation\Http\Middleware\CheckForMaintenanceMode::class
    ];

    /**
     * The application's route middleware groups.
     *
     * @var array
     */
    protected $middlewareGroups = [
        'web' => [
            \App\Http\Middleware\EncryptCookies::class,
            \Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse::class,
            \Illuminate\Session\Middleware\StartSession::class,
            \Illuminate\View\Middleware\ShareErrorsFromSession::class,
            \App\Http\Middleware\VerifyCsrfToken::class,
            \Illuminate\Routing\Middleware\SubstituteBindings::class,
            \App\Http\Middleware\HttpsProtocol::class

        ],

        'api' => [
            'throttle:60,1',
            'bindings',
        ],
    ];

    /**
     * The application's route middleware.
     *
     * These middleware may be assigned to groups or used individually.
     *
     * @var array
     */
    protected $routeMiddleware = [
        'login'      => \App\Http\Middleware\Login::class,
        'language'   => \App\Http\Middleware\Language::class,
        'authorize'  => \App\Http\Middleware\Authorization::class,
        'auth'       => \Illuminate\Auth\Middleware\Authenticate::class,
        'auth.basic' => \Illuminate\Auth\Middleware\AuthenticateWithBasicAuth::class,
        'bindings'   => \Illuminate\Routing\Middleware\SubstituteBindings::class,
        'can'        => \Illuminate\Auth\Middleware\Authorize::class,
        'guest'      => \App\Http\Middleware\RedirectIfAuthenticated::class,
        'throttle'   => \Illuminate\Routing\Middleware\ThrottleRequests::class,
        'logging'    => \App\Http\Middleware\Logging::class,
        'logRequest' => \App\Http\Middleware\LogRequest::class
    ];

    protected $customBootstrappers = [
        'App\Http\Bootstrappers\Environment'
    ];

    /**
     * Get the bootstrap classes for the application.
     *
     * @return array
     */
    protected function bootstrappers()
    {
        $this->bootstrappers = array_merge($this->customBootstrappers, $this->bootstrappers);
        return $this->bootstrappers;
    }
}
