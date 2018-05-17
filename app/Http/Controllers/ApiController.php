<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Device;
use App\Image;
use App\Video;
use League\Flysystem\Config;
use RobbieP\CloudConvertLaravel\CloudConvert;


class ApiController extends Controller
{
    public function imageSave(Request $request){

        $cam1 = $request->file('image');
        $cam2 = $request->file('image2');
        $text = $request->file('text');

//        echo "<pre>";
//        print_r($cam1->getClientOriginalName());
//        echo "</pre>";
//
//        die();

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
            $path = $request->file('image')->storeAs('public/data/' . $device->id . '/camera1', $cam1->getClientOriginalName());
            $image = new Image;
            $image->name = $path;
            $image->device_id = $device->id;
            $image->camera_number = 1;
            $image->save();
            $msg[] = "I have recived camera 1 screen";
        }
        if(isset($cam2)){
            $path = $request->file('image2')->storeAs('public/data/' . $device->id . '/camera2',$cam2->getClientOriginalName());
            $image = new Image;
            $image->name = $path;
            $image->device_id = $device->id;
            $image->camera_number = 2;
            $image->save();
            $msg[] = "I have recived camera 2 screen";
        }
        if(isset($text)){
            $path = $request->file('text')->storeAs('public/data/' . $device->id . '/text', $text->getClientOriginalName());
            $msg[] = "I have recived text";
        }

        return response()->json([
            'status' => 'success',
            'msg' => $msg,
            'alert' => "Abundant Active Aptitude"
            ], 200);

    }


    public function videoSave(Request $request){

        //die();

        $video = $request->file('video');

        if(isset($video)){
            $path = $request->file('video')->storeAs('public/data/video', $video->getClientOriginalName());

            $convert = new CloudConvert();

            $convert->setApiKey('AWQmdtFL909VRgdqJnvKAh7nXcQv7UfrgxsVc7H0XkfBQQ2SEma6uwALQcj28yR8');
            $convert->file($path)->to('mp4');
            $msg[] = "I have recived video";
        }

        return response()->json([
            'status' => 'success',
            'msg' => $msg,
            'alert' => "Abundant Active Aptitude"
        ], 200);

    }

    public function setVideoRequired(Request $request){


        $path = $request->path;
        //echo $request->path;
        $path_elem = explode('/', $path);
        //print_r($path_elem);
        $last = $path_elem[count($path_elem)-1];
        //echo $last;
        $second = explode('.', $last);
        $file_name = $second[0];
        //echo $file_name;
        $video = new Video;
        $video->name = $file_name;
        $video->save();


    }

    public function videoList(){

        $videos = Video::where('downloaded', 0)->get();
        Video::where('downloaded', 0)->update(['downloaded' => 1]);
        $videosArr = $videos->toArray();

        $result = [];


        foreach ($videos as $video){
            $result[] = $video->name;
            //print_r($video);
        }

        return response()->json($result);
    }

}
