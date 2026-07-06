<?php

namespace App\Http\Controllers;

use App\Helper\Helper;

use App\Models\Game;
use App\Models\Host;
use App\Models\Message;
use App\Models\State;
use App\Models\Ticket;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class HomeController extends Controller{
    function getSCA(){
        return State::with('city.area')->get();
    }
    function getGameData(){
        $cafe=Host::with(['ticket'=>function($query){
            if (isset($_GET['game'])){
                $query->ticket->where('title','=',$_GET['game']);
            }
        }]);
        if (isset($_GET['name'])){
            Helper::getByCafeName($cafe);
            return response()->json(['cafe'=>$cafe]);
        }else{
            $ticket=Helper::getByTicket();
            return response()->json(['ticket'=>$ticket]);
        }
    }
    function games(Request $request){
        return Game::where('title','LIKE','%'.$request->title.'%')->get();
    }

    function aboutUs(){
        $user=User::all()->count();
        $host=Host::all()->count();
        $tickets=Ticket::all()->count();
        $games=Game::all()->count();
        return response()->json(['user'=>$user,'host'=>$host,'tickets'=>$tickets,'games'=>$games]);
    }
}
