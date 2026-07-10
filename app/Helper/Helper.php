<?php

namespace App\Helper;

use App\Models\Host;
use App\Models\Ticket;
use Carbon\Carbon;
use Illuminate\Support\Facades\Http;

class Helper{
    static function getByCafeName($cafeName){
        foreach (array_diff_key($_GET,['game'=>0]) as $key=>$value) {
            if ($key==='name'){
                $cafeName->where('name','LIKE','%'.$value.'%');
            }else{
                $cafeName->where($key,'=',$value);
            }
            $cafeName->orderBy('like','desc')->get();
        }
    }
    static function getByTicket(){
        $ticket=Ticket::query();
        foreach ($_GET as $key=>$value){
            $ticket->where($key,'=',$value);
        }
        return $ticket->get();
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
}
