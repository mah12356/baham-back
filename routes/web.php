<?php

use App\Helper\Helper;
use App\Models\Host;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;


Route::get('/', function (Request $request) {
    $path = "بارگیری.jpeg";

//    return response(
//        Storage::disk('s3')->get($path),
//        200,
//        [
//            'Content-Type' => Storage::disk('s3')->mimeType($path),
//        ]
//    );
    return view('welcome',compact('path'));
});
Route::get('/image/{file}',function($file){
    $path = "images/$file";

    return response(
        Storage::disk('s3')->get($path),
        200,
        [
            'Content-Type' => Storage::disk('s3')->mimeType($path),
        ]
    );
})
    ->where('file', '.*')
    ->name('image');
