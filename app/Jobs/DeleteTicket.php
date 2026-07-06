<?php

namespace App\Jobs;

use App\Helper\Sms;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class DeleteTicket implements ShouldQueue
{
    use Queueable;
    public $ticket;
    /**
     * Create a new job instance.
     */
    public function __construct($ticket){
        $this->ticket = $ticket;
    }
    /**
     * Execute the job.
     */
    public function handle(): void{
        foreach ($this->ticket as $item) {
            foreach ($item->reservation as $value) {
                Sms::deleteTicket($value->user->phone,$this->ticket);
                $value->delete();
                sleep(0.5);
            }
            $item->delete();
        }
    }
}
