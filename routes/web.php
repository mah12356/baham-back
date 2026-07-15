<?php

use App\Helper\Helper;
use App\Models\Host;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;


Route::get('/', function () {
//    return view('welcome');
    try{
        $res=Http::get('https://api.geoapify.com/v1/geocode/search?text='.null.', Iran&&lang=fa&format=json&apiKey=075b4f1093c847c5b6aa416496b225fd');
        if (count($res['results'])===0){
            $i=0;
        }
        else {
            $result = $res['results'];
            foreach ($result as $item) {
                if ($item['state'] === null && $item['city'] === null && $item['neighbourhood']===null) {
                    $i=1;
                    break;
                }else{
                    $i=0;
                }
            }
        }
        return $i;
    }catch (\Exception $e){
        return 'false';
    }

//        foreach ($result as $item) {
//            if ($item['state'] === $state && $item['city'] === $city && $item['neighbourhood']===$area) {
//                $i=1;
//                break;
//            }else{
//                $i=0;
//            }
//        }

//    return Helper::verifyLoc(null,null,null);
});
