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
    function editHost(Request $req){
        $host=auth('host')->user();
        $host->name=$req->name;
        $host->phone=$req->phone;
        $setLikeToZero=false;
        $id=$host->id;
        /// اگر میزبان بخواد آدرس میزبانیش رو عوض کنه امتیاز هاش رو از دست میده
        if ($host->address!==$req->address){
            $host->address=$req->address;
            $setLikeToZero=true;
        }
        if ($host->city===$req->city){
            $host->city=$req->city;
            $setLikeToZero=true;
        }
        if ($host->area===$req->area){
            $host->area===$req->area;
            $setLikeToZero=true;
        }
        if ($host->state===$req->state){
            $host->state=$req->state;
            $setLikeToZero=true;
        }
        if ($setLikeToZero===true){
            $host->likes=0;
        }
        $likes=Like::where('host_id',$id)->get();
        foreach ($likes as $like){
            $like->delete();
        }
        if ($host->save()){
            return response()->json([]);
        }else{
            return response()->json(['message'=>'خطای سرور'],400);
        }
    }

    function editHostPhoto(Request $request){
        $host=auth('host')->user();
        $file=$request->file('photo');
        $filename=time().'.'.$file->getClientOriginalName();
        if ($host->photo!==null){
            unlink('/storage/cafes/'.$host->photo);
        }
        $host->photo=$filename;
        $file->move(public_path('/storage/cafes'),$filename);
        if ($host->save()){
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
