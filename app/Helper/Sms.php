<?php

namespace App\Helper;

use Illuminate\Support\Facades\Storage;

class Sms{
    static function changeTime($phone,$ticket){

    }
    static function deleteTicket($phone,$ticket){

    }
    static function checkSizeOfStorage(){
        $files = Storage::disk('s3')->allFiles();
        $totalSize = 0;

        foreach ($files as $file) {
            $totalSize += Storage::disk('s3')->size($file);
        }
        $used = $totalSize; // bytes
        $capacity = 5 * 1024 * 1024 * 1024; // 5 GB in bytes
        $remaining = $capacity - $used;
        if (round($remaining/1024/1024,2)<=60){
            // ویام برای ارتغا فضای ابری
        }
    }
}
