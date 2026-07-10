<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use PHPOpenSourceSaver\JWTAuth\Contracts\JWTSubject;

class Host extends Authenticatable implements JWTSubject
{
    use HasFactory, Notifiable;
    public $appends=['picture'];
    public $hidden=['photo'];
    function getPictureAttribute(){
        return asset('host/'.$this->photo);
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
