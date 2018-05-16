<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Device;
use App\Image;
use App\Video;

class ApiController extends Controller
{
    public function imageSave(Request $request){

        $cam1 = $request->file('image');
        $cam2 = $request->file('image2');
        $text = $request->file('text');

        $msg = [];

        // 6549842165489
        // 159

        $deviceSerialNumber = $request->serial_number;
        $devicePass = $request->password;

        $device = Device::where('serial_number', $deviceSerialNumber)->where('password', $devicePass)->first();
        if($device === null){
            return response()->json([
                'status' => 'error',
                'msg' => 'device not found',
                'alert' => "Abundant Active Aptitude"
            ], 200);
        }




        if(isset($cam1)){
            $path = $request->file('image')->store('public/data/' . $device->id . '/camera1');
            $image = new Image;
            $image->name = $path;
            $image->device_id = $device->id;
            $image->camera_number = 1;
            $image->save();
            $msg[] = "I have recived camera 1 screen";
        }
        if(isset($cam2)){
            $path = $request->file('image2')->store('public/data/' . $device->id . '/camera2');
            $image = new Image;
            $image->name = $path;
            $image->device_id = $device->id;
            $image->camera_number = 2;
            $image->save();
            $msg[] = "I have recived camera 2 screen";
        }
        if(isset($text)){
            $path = $request->file('text')->store('public/data/' . $device->id . '/text');
            $msg[] = "I have recived text";
        }

        return response()->json([
            'status' => 'success',
            'msg' => $msg,
            'alert' => "Abundant Active Aptitude"
            ], 200);

    }


    public function videoSave(Request $request){

        $video = $request->file('video');

        if(isset($video)){
            $path = $request->file('video')->store('public/data/video');
            $msg[] = "I have recived video";
        }

        return response()->json([
            'status' => 'success',
            'msg' => $msg,
            'alert' => "Abundant Active Aptitude"
        ], 200);

    }

    public function videoList(){
        return response()->json([
            'cam2_16.05.2018.12.59.52'
        ]);
    }

}
