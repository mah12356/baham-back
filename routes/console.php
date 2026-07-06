<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;
use App\Models\Host;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');
Schedule::call(function (){
    $host=Host::with('score')->get();
    foreach ($host as $item) {
        $item->like=$item->count();
        $item->save();
    }
});
