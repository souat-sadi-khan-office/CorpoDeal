<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote')->hourly();

Schedule::command('queue:work --stop-when-empty --queue=high')->everyMinute();
Schedule::command('queue:work --stop-when-empty --queue=medium')->everyMinute();
Schedule::command('queue:work --stop-when-empty --queue=low')->everyMinute();
Schedule::command('queue:work --stop-when-empty --queue=emails')->everyMinute();
Schedule::command('queue:retry all')->everyFiveMinutes();
Schedule::command('activity:cleanup')->daily();
