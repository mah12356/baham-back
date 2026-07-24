<?php

namespace App\Jobs;

use App\Models\Host;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class Like implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void{
        $host=Host::with('score')->get();
        foreach ($host as $item) {
            $item->like=$item->count();
            $item->save();
        }
    }
}
