<?php

use Illuminate\Support\Facades\Route;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\Request;

Route::get('/', function (Request $request) {
    $filename=$request->file('file')->getClientOriginalName();
    $request->file('file')->store([
        'disk'=>'s3',
        'visibility'=>'public'
    ]);
//$res=\Illuminate\Support\Facades\Http::get('http://api.geonames.org/searchJSON?q=ولنجک&username=mahdi');
//$res=\Illuminate\Support\Facades\Http::get('https://api.geoapify.com/v1/geocode/search?text=زعفرانیه, Iran&&lang=fa&format=json&apiKey=075b4f1093c847c5b6aa416496b225fd');
//return $res->json();
});
