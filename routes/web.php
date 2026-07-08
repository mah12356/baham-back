<?php

use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Route;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\Request;

Route::get('/', function () {
    $host=\App\Models\Host::create([
        'username'=>'qqwqw',
        'national_code'=>'0019890141',
        'password'=>Hash::make('mah123'),
        'phone'=>'09377965228',
        'address'=>'chhfc',
        'city'=>'chhfc',
        'state'=>'chhfc',
        'area'=>'chhfc',
        'photo'=>'qqwqw'
    ]);
//$res=\Illuminate\Support\Facades\Http::get('http://api.geonames.org/searchJSON?q=ولنجک&username=mahdi');
//$res=\Illuminate\Support\Facades\Http::get('https://api.geoapify.com/v1/geocode/search?text=زعفرانیه, Iran&&lang=fa&format=json&apiKey=075b4f1093c847c5b6aa416496b225fd');
//return $res->json();
});
