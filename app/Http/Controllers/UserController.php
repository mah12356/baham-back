<?php

namespace App\Http\Controllers;

use App\Helper\Helper;
use App\Models\Comment;
use App\Models\Host;
use App\Models\Like;
use App\Models\Reservation;
use App\Models\Ticket;
use App\Models\U_wallets;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class UserController extends Controller{
    function reserve(Request $req){
        $user=auth('api')->user();
        $ticket=Ticket::find($req->ticket_id);
        $wallet=U_wallets::where('user_id',$user->id)->first();
        $tickets=Ticket::where('host_id',$ticket->host_id)->count();
        if ($ticket->players===$tickets){
            return response()->json(['message'=>'گنجایش تمام بلیت پر شده'],403);
        }elseif ($user->shaba===null){
            return response()->json(['message'=>'برای رزرو بلیت باید شماره شبا داشته باشید'],403);
        }elseif ($wallet->irt< $tickets->cost){
            return response()->json(['message'=>'کیف پول شما موجودی از ارزش بلیت کمتر است'],403);
        }
        else{
            $reservation=new Reservation();
            $reservation->user_id=$user->id;
            $reservation->ticket_id=$req->ticket_id;
            $reservation->save();
            return response()->json(['message'=>'']);
        }
    }
    function userProfile(){
        $user=auth('api')->user();
        $reservation=Reservation::where('user_id',$user->id)->with('ticket.host')->get();
        return response()->json(['user'=>$user,'reservation'=>$reservation]);
    }
    function likeCafe(Request $request){
        $user=auth('api')->user();
        $like=Like::where(['host_id'=>$user->id,'user_id'=>$user->id])->first();
        if ($like===null){
            $like=new Like();
            $like->host_id=$request->id;
            $like->user_id=$user->id;
            if ($like->save()){
                return 'liked';
            }else{
                return response()->json(['message'=>'لایک انجام نشد مشکل از سرور'],422);
            }
        }else{
            if ($like->delete()){
                return 'deleted';
            }else{
                return response()->json(['message'=>'دیس لایک انجام نشد مشکل از سرور'],422);
            }
        }
    }
    function comments(Request $request){
        return Comment::where('host_id',$request->id)->get();
    }

    function addComment(Request $request){
        $user=auth('api')->user();
        $host=Host::find($request->id);
        $comment=new Comment();
        $comment->user_id=$user->id;
        $comment->host_id=$host->id;
        $comment->message=$request->message;
        $comment->save();
        return response()->json($comment);
    }
    function updateUser(Request $request){
        Log::debug($request);
        $user=auth('api')->user();
        if ($request->username!==null){
            $user->username=$request->username;
        }elseif ($request->phone!==null){
            $user->phone=$request->phone;
        }
        $user->save();
    }


}
