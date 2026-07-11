<?php

use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Route;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\Request;

Route::get('/', function () {
    unlink('host/Screenshot from 2026-07-08 14-55-50.png');
});
