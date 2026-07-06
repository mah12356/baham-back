<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Morilog\Jalali\Jalalian;

class Ticket extends Model{
    public $timestamps = false;
    public $appends=['jalali','jTime','reservations'];
    public $hidden=['date'];
    function getJalaliAttribute(){
        return Jalalian::fromDateTime($this->date)->format('Y-m-d');
    }
    function getReservationsAttribute(){
        return Reservation::where('ticket_id',$this->id)->count();
    }
    function getJTimeAttribute(){
        return Jalalian::forge($this->time)->format('H:i');
    }
    function host(){
        return $this->belongsTo(Host::class,'host_id','id');
    }
    function reservation(){
        return $this->hasMany(Reservation::class,'ticket_id','id');
    }
}
