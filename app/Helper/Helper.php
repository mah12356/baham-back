<?php

namespace App\Helper;

use App\Models\Ticket;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class Helper{
    static function getByCafeName($host,$get){
        foreach ($get as $key=>$value) {
            $host->where($key,$value);
        }
        return $host->get();
    }
    static function getByTicket(){
        $ticket=Ticket::query();
        foreach ($_GET as $key=>$value){
            $ticket->where($key,'=',$value);
        }
        return $ticket->with('host')->get();
    }
    static function getUser(){
        $token=new Token();
        return $token->getTokenType();
    }
    static function shaba($shaba,$nid){
        $res=Http::withHeaders([
            'Content-Type' => 'application/json',
            'Authorization'=>'Bearer 51afdf047240accbd8e46fdf86fd12e2192e122e'
        ])->post('https://service.zohal.io/api/v0/services/inquiry/check_iban_with_national_code',[
            'IBAN'=>$shaba,
            'birth_date'=>'1377/07/19',
            'national_code'=>$nid
        ]);
        $result=$res->json();
        if (isset(['response_body']['data']['matched'])){
            $matched=$result['response_body']['data']['matched'];
            if ($matched===true){
                return true;
            }else{
                return false;
            }
        }else{
            return 'خطای اتصال';
        }
    }
    static function phone($phone,$nid){
        $res=Http::withHeaders([
            'Content-Type' => 'application/json',
            'Authorization'=>'Bearer 51afdf047240accbd8e46fdf86fd12e2192e122e'
        ])->post('https://service.zohal.io/api/v0/services/inquiry/shahkar',[
            'mobile'=>$phone,
            'national_code'=>$nid
        ]);
        $result=$res->json();
        if (isset($result['response_body']['data']['matched'])){
            $matched=$result['response_body']['data']['matched'];
            if ($matched===true){
                return true;
            }else{
                return false;
            }
        }else{
            return 'خطای اتصال';
        }
    }
    static function verifyLoc($area,$city,$state){
        try{
            $res=Http::get('https://api.geoapify.com/v1/geocode/search?text='.$area.', Iran&&lang=fa&format=json&apiKey=075b4f1093c847c5b6aa416496b225fd');
            if (count($res['results'])===0){
                $i=0;
            }
            else {
                $result = $res['results'];
                foreach ($result as $item) {
                    if ($item['state'] === $state && $item['city'] === $city && $item['neighbourhood']===$area) {
                        $i=1;
                        break;
                    }else{
                        $i=0;
                    }
                }
            }
            return $i;
        }catch (\Exception $e){
            return false;
        }
    }
}
