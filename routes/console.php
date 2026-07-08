<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
Schedule::command('bookings:cancel-expired')->hourly();

use Illuminate\Support\Facades\Schedule;

Schedule::command('schedules:purge-expired')->hourly();

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');
