<?php

namespace App\Http\Controllers;

use App\Helper\Helper;
use App\Helper\Sms;
use App\Jobs\DeleteTicket;
use App\Jobs\SaveTicket;
use App\Jobs\SmsforChangingTicketDateTime;
use App\Models\Comment;
use App\Models\Game;
use App\Models\Host;
use App\Models\Like;
use App\Models\Ticket;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Morilog\Jalali\Jalalian;

class CafeController extends Controller
{
    function saveTicket(Request $req){
        (new SaveTicket($req))->handle();
        $game=Game::where('title',$req->title);
        if ($game===null){
            $game=new Game();
            $game->title=$req->title;
            $game->save();
        }
        return response()->json(['message'=>'بلیت شما ذخیره شد']);
    }
    function hostProfile(){
        $user=auth('host')->user();
        $tickets=Ticket::where('host_id',$user->id)->with('reservation.users')->get();
        return response()->json(['tickets'=>$tickets,'user'=>$user]);
    }
    function cafeProfile($id){
        return Host::with('ticket')->find($id);
    }
    function answer(Request $req){
        $comment=Comment::find($req->id);
        $comment->answer=$req->answer;
        if ($comment->save()){
            return response()->json([]);
        }else{
            return response()->json(['message'=>'خطای سرور'],400);
        }
    }

    function editTicketDateTime(Request $request){
        $user=auth('host')->user();
        $ticket=Ticket::where(['id'=>$request->id,'host_id'=>$user->id])->with('reservation.users')->first();
        $ticket->date=$request->date;
        $ticket->time=$request->time;
        (new SmsforChangingTicketDateTime($ticket))->handle();
        if ($ticket->save()){
            //ثبلاذwd
            return response()->json([]);
        }else{
            return response()->json(['message'=>'خطای سرور'],400);
        }
    }
    function deleteTicket(Request $request){
        $user=auth('host')->user();
        $ticket=Ticket::where(['id'=>$request->id,'host_id'=>$user->id])->with('reservation.users')->first();
        (new DeleteTicket($ticket))->handle();
    }
    function hostPage(Request $request){
        $user=auth('api')->user();
        $host=Host::with('ticket.reservation')->find($request->id);
        foreach ($host->ticket as $item) {
            foreach ($item->reservation as $value) {
                if ($user->id===$value->user_id){
                    $item->reserved=true;
                }else{
                    $item->reserved=false;
                }
            }
        }
        return response()->json(['host'=>$host]);
    }
}
