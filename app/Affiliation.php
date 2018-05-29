<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Device;

class Affiliation extends Model
{
    public function getDevice(){
        $device = Device::where('id', $this->device_id)->orderBy('id', 'desc')->first();
        $coordinates['latitude'] = $device->locationLatLng()->latitude;
        $coordinates['longitude'] = $device->locationLatLng()->longitude;
        return $coordinates;
    }

    public function getUser(){
        return $this->belongsTo('App\User', 'affiliate_user_id');
    }

}