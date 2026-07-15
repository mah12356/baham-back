<?php

namespace App\Jobs;

use App\Helper\Sms;
use App\Models\H_wallets;
use App\Models\Host;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;

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
        $filename=$time.$photo->getClientOriginalName();
        Sms::checkSizeOfStorage();
        Storage::disk('s3')->putFileAs(
            'host',
            $photo,
            $filename
        );
        $data['photo']=$filename;
        $host=Host::create($data);
        Session::put('host',$host);
        $wallet=new H_wallets();
        $wallet->host_id=$host->id;
        $wallet->save();
    }
}
