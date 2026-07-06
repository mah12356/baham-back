<?php

namespace App\Jobs;

use App\Models\H_wallets;
use App\Models\Host;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Session;

class CreateHost implements ShouldQueue
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
    public function handle(): void
    {
        $time=time();
        $data=$this->request->except(['password','photo']);
        $data['password']=sha1($this->request->password);
        $photo=$this->request->file('photo');
        $photo->move(public_path('/cafes'),$time.$photo->getClientOriginalName());
        $data['photo']=$time.$photo->getClientOriginalName();
        $host=Host::create($data);
        Session::put('host',$host);
        $wallet=new H_wallets();
        $wallet->host_id=$host->id;
        $wallet->save();

    }
}
