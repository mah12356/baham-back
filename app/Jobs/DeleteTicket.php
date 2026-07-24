<?php

namespace App\Jobs;

use App\Helper\Sms;
use App\Models\Ticket;
use Carbon\Carbon;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class DeleteTicket implements ShouldQueue
{
    use Queueable;
    /**
     * Create a new job instance.
     */
    public function __construct(){
    }
    /**
     * Execute the job.
     */
    public function handle(): void{
        $yesterday = Carbon::yesterday();
        $tickets=Ticket::where('date',[
            $yesterday->copy()->startOfDay(),
            $yesterday->copy()->endOfDay()
        ])->get();
        foreach($tickets as $ticket){
            $ticket->delete();
        }
    }
}
