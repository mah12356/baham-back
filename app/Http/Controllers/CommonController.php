<?php

namespace App\Http\Controllers;

use App\Helper\Helper;
use App\Models\H_wallets;
use App\Models\U_wallets;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class CommonController extends Controller
{
    function saveAmount(Request $request){
        if (auth('api')->user()===null){
            $type='host';
        }else{
            $type='user';
        }
        Session::put('type',$type);
        Session::put('amount',$request->amount);
    }

    function updateWallet(){
        $tokenType=Helper::getUser();
        if (isset($tokenType->city)){
            $wallet=H_wallets::where('host_id',$tokenType->id)->first();
        }else{
            $wallet=U_wallets::where('user_id',$tokenType->id)->first();
        }
        return $wallet->irt;
    }
}
