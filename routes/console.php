<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;
use App\Models\Host;
use App\Jobs\DeleteTicket;
use App\Jobs\Like;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');
Schedule::job(new DeleteTicket())->dailyAt('03:00');
Schedule::job(new Like())->everyFiveMinutes();
