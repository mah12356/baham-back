<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Storage;
use PHPOpenSourceSaver\JWTAuth\Contracts\JWTSubject;

class Host extends Authenticatable implements JWTSubject
{
    use HasFactory, Notifiable;
    public $appends=['picture'];

    function getPictureAttribute(){
        $path = "host/$this->photo";
        return response(Storage::disk('s3')->temporaryUrl($path,now()->addMinutes(30)));
    }
    public $timestamps = false;
    public $fillable=['city','area','state','photo','national_code','address','username','phone','password'];
    function ticket(){
        return $this->hasMany(Ticket::class,'host_id','id');
    }
    function score(){
        return $this->hasMany(Like::class,'host_id','id');
    }
    function getJWTIdentifier(){
        return $this->getKey();
    }

    function getJWTCustomClaims(){
        return [];
    }
}
