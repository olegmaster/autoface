<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Device;

class Message extends Model
{
    public function device(){
        $device = Device::find($this->device_id);

        return $device;
    }
}
