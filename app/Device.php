<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Path;


class Device extends Model
{
    public function location(){

// AIzaSyCB4skjUVhWhXc8YloGFNGYqpdr_5-_oPk

        $path = Path::where('device_id', $this->id)->orderBy('id', 'desc')->first();
        if($path){
            $path->getAddressByCoordinates();
            $response = $path->getAddressByCoordinates();;
        } else{
            $response = false;
        }

        return $response;
    }

    public function obtainPath(){
        return $this->hasMany('App\Path');
    }

    public function getDevicesOfCurrentUser(){

    }

    public function locationLatLng(){
        $path = Path::where('device_id', $this->id)->orderBy('id', 'desc')->first();
        return $path;
    }

    public function getDeviceBigNoise(){
        return $this->belongsTo('App\User', 'user_id');
    }

}
