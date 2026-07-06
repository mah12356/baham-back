<?php

namespace App\Jobs;

use App\Models\User;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class CreateUser implements ShouldQueue
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


    }
}
