<?php

namespace App\Jobs;

use App\Helper\Sms;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class SmsforChangingTicketDateTime implements ShouldQueue
{
    use Queueable;
    public $ticket;
    /**
     * Create a new job instance.
     */
    public function __construct($ticket){
        $this->ticket=$ticket;
    }
    /**
     * Execute the job.
     */
    public function handle(): void{
        foreach ($this->ticket->reservation->users as $user) {
            Sms::changeTime($user->phone,$this->ticket);
            sleep(0.5);
        }
    }
}
