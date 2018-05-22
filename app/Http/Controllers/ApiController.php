<?php

namespace App\Http\Controllers;

use App\Path;
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
            $fileLines = $this->convertTextFileToArrayOfLines($path);

            if($this->checkNavigatorIsWorking($fileLines)){
                $coordinates = $this->getCoordinatesFromFileLines($fileLines);
                $this->saveCoordinatesToDatabase($coordinates, $device->id);
            }

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

            //$convert = new CloudConvert();

            //$convert->setApiKey('AWQmdtFL909VRgdqJnvKAh7nXcQv7UfrgxsVc7H0XkfBQQ2SEma6uwALQcj28yR8');
            //$convert->file($path)->to('mp4');
            $msg[] = "I have recived video";
            shell_exec("sh /home/pz257197/auto-face.meral.com.ua/www/c.sh");
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

        $result = [];

        $videosOnEvents = Video::where('downloaded', 0)->orderBy('id', 'desc')->take(1)->get();

        $videos = Video::where('downloaded', 0)->get();


        Video::where('downloaded', 0)->update(['downloaded' => 1]);


//        foreach ($videosOnEvents as $video){
//            $elem = [];
//            $elem['video_name'] = $video->name;
//            $elem['state'] = 'signaling';
//            $result[] = $elem;
//        }

        foreach ($videos as $video){
            $result[] = $video->name;
        }

        return response()->json($result);
    }

    private function convertTextFileToArrayOfLines($path){
        $result = [];
        $handle = fopen($path, "r");
        if($handle){
            while(($line = fgets($handle)) !== false ){
                $result[] = $line;
            }
            fclose($handle);
        }
        return $result;
    }

    private function getLatitudeLongitudeFromLine($line){
        $lineExploded = explode(',', $line);
        $latitude = isset($lineExploded[1]) ? $lineExploded[1] : '';
        $longitude = isset($lineExploded[3]) ? $lineExploded[3] : '';
        return [$latitude, $longitude];
    }

    private function saveCoordinatesToDatabase($coordinates, $deviceId){

        $path = new Path;

        foreach($coordinates as $coordinate){
            $path->device_id = $deviceId;
            $path->latitude = $coordinate[0];
            $path->longitude = $coordinate[1];
            $path->save();
        }

    }

    private function getCoordinatesFromFileLines($fileLines){
        $coordinates = [];
        foreach($fileLines as $line){
            if($line != ''){
                $coordinate = $this->getLatitudeLongitudeFromLine($line);

                if(!empty($coordinate[0]) && !empty($coordinate[1])){
                    $coordinates[] = $coordinate;
                }
            }
        }

        return $coordinates;
    }

    private function checkNavigatorIsWorking($fileLines){
        if($fileLines[0] == '-'){
            return false;
        }
        return true;
    }

}
