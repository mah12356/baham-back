<?php

namespace App\Jobs;

use App\Models\H_wallets;
use App\Models\Host;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Hash;
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
        $data['password']=Hash::make($this->request->password);
        $photo=$this->request->file('file');
        $photo->move(public_path('/host'),$time.$photo->getClientOriginalName());
        $data['photo']=$time.$photo->getClientOriginalName();
        $host=Host::create($data);
        Session::put('host',$host);
        $wallet=new H_wallets();
        $wallet->host_id=$host->id;
        $wallet->save();
    }
}
