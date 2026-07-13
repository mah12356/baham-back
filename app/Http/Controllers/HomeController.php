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
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;

class HomeController extends Controller{

    function getGameData(){
        if (isset($_GET['username'])){
            $host=Host::query();
            unset($_GET['game']);
            $newHost=Helper::getByCafeName($host,$_GET);
            return response()->json(['host'=>$newHost]);
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
