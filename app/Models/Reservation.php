<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Reservation extends Model
{
    public $timestamps = false;
    function ticket(){
        return $this->belongsTo(Ticket::class,'ticket_id','id');
    }
    function users(){
        return $this->belongsTo(User::class,'user_id','id');
    }
}
