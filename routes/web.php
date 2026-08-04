<?php

use App\Helper\Helper;
use App\Models\Host;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;


Route::get('/', function () {
//440560213780004971393001
    $r= Helper::shaba('440560213780004971393001','0019890141');

    echo $r['response_body']['data']['matched'];
});
