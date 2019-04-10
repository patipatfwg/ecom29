<?php

use Illuminate\Foundation\Inspiring;
use App\Console\ScheduleBatchSms;

/*
|--------------------------------------------------------------------------
| Console Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of your Closure based console
| commands. Each Closure is bound to a command instance allowing a
| simple approach to interacting with each command's IO methods.
|
*/

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->describe('Display an inspiring quote');


// Artisan::command('schedule:sms {datetime?}', function (ScheduleBatchSms $schedule, $datetime) {
//     $this->info("Send sms batch file : {$datetime}");
//     // $schedule->test();

// });