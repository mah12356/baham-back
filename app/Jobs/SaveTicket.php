<?php

namespace App\Jobs;

use App\Models\Ticket;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Morilog\Jalali\Jalalian;

class SaveTicket implements ShouldQueue
{
    use Queueable;
    public $request;
    public function __construct($request)
    {
        $this->request = $request;
    }
    /**
     * Create a new job instance.
     */
    /**
     * Execute the job.
     */
    public function handle(): void{
        $ticket=new Ticket();
        $user=auth('host')->user();
        $ticket->host_id=$user->id;
        $ticket->game=$this->request->game;
        $ticket->players=$this->request->players;
        $ticket->date=explode('T',$this->request->date)[0];
        $ticket->time=$this->request->time;
        $ticket->city=$user->city;
        $ticket->state=$user->state;
        $ticket->area=$user->area;
        if ($this->request->reward!==null){
            $ticket->reward=$this->request->reward;
        }
        $ticket->save();
    }
}
