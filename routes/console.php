<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Phase 4: schedule the reschedule-reminder job here, e.g.
// use Illuminate\Support\Facades\Schedule;
// Schedule::command('reminders:dispatch')->dailyAt('08:00');
