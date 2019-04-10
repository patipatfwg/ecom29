<?php

namespace App\Console;

use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\Events\Dispatcher;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    public function __construct(Application $app, Dispatcher $events)
    {
        parent::__construct($app, $events);

        array_walk($this->bootstrappers, function(&$bootstrapper)
        {
            if($bootstrapper === 'Illuminate\Foundation\Bootstrap\ConfigureLogging')
            {
                $bootstrapper = 'App\Http\Bootstrappers\ConfigureLogging';
            }
        });
    }

    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        Commands\SendSmsFile::class,
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // $schedule->command('inspire')
        //          ->hourly();
    }

    /**
     * Register the Closure based commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        require base_path('routes/console.php');
    }

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
