<?php

use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Route;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\Request;

Route::get('/', function () {
    $f=\App\Helper\Helper::phone('09377965228','0019890141');
    return $f;
});
