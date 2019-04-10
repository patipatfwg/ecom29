<?php 
namespace App\Http\Bootstrappers;

use Monolog\Logger as Monolog;
use Illuminate\Log\Writer;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Foundation\Bootstrap\ConfigureLogging as BaseConfigureLogging;
use Monolog\Handler\StreamHandler;
use Monolog\Formatter\LineFormatter;

class ConfigureLogging extends BaseConfigureLogging
{

    /**
     * OVERRIDE PARENT
     * Configure the Monolog handlers for the application.
     *
     * @param  \Illuminate\Contracts\Foundation\Application  $app
     * @param  \Illuminate\Log\Writer  $log
     * @return void
     */
    protected function configureHandlers(Application $app, Writer $log)
    {
        // // Date
        // $day = date('Y-m-d');
        // $time = date('H');

        // // the default date format is "Y-m-d H:i:s"
        // $dateFormat = "Y-m-d H:i:s";
        // // the default output format is "[%datetime%] %channel%.%level_name%: %message% %context% %extra%\n"
        // $output = "[%datetime%] %channel%.%level_name%: %message% %context%\n";
        // // finally, create a formatter
        // $formatter = new LineFormatter($output, $dateFormat);

        // $bubble = false;

        // $pathInfo = sprintf('%s/info/%s/%s.log',
        //     env('LOG_PATH'),
        //     $day,
        //     $time
        // );

        // $pathWaring = sprintf('%s/warning/%s/%s.log',
        //     env('LOG_PATH'),
        //     $day,
        //     $time
        // );

        // $pathError = sprintf('%s/error/%s/%s.log',
        //     env('LOG_PATH'),
        //     $day,
        //     $time
        // );

        // // Stream Handlers
        // $infoStreamHandler = new StreamHandler($pathInfo, Monolog::INFO, $bubble);
        // $warningStreamHandler = new StreamHandler($pathWaring, Monolog::WARNING, $bubble);
        // $errorStreamHandler = new StreamHandler($pathError, Monolog::ERROR, $bubble);

        // $infoStreamHandler->setFormatter($formatter);
        // $warningStreamHandler->setFormatter($formatter);
        // $errorStreamHandler->setFormatter($formatter);

        // // Get monolog instance and push handlers
        // $monolog = $log->getMonolog();
        // $monolog->pushHandler($infoStreamHandler);
        // $monolog->pushHandler($warningStreamHandler);
        // $monolog->pushHandler($errorStreamHandler);

        // // $log->useDailyFiles($app->storagePath().'/logs/daily.log');
    }
}