<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SignalTest extends Model
{
    public function getDevice(){
        return $this->belongsTo('App\Device', 'device_id');
    }
}
