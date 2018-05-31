<?php

namespace App\Http\Controllers;

use App\AlarmVideo;
use App\Path;
use App\SignalTest;
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

        $msg = [];

        $deviceSerialNumber = $request->serial_number;
        $devicePass = $request->password;

        $device = $this->authDevice($deviceSerialNumber, $devicePass);

        if(!$device){
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

        $deviceSerialNumber = $request->serial_number;
        $devicePass = $request->password;
        $device = $this->authDevice($deviceSerialNumber, $devicePass);

        if(!$device){
            return response()->json([
                'status' => 'error',
                'msg' => 'device not found',
                'alert' => "Abundant Active Aptitude"
            ], 200);
        }

        $video = $request->file('video');

        if(isset($video)){
            $path = $request->file('video')->storeAs('public/data/' . $device->id . '/video/', $video->getClientOriginalName());

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

        if(isset($request->password) && isset($request->serial_number)) {
            $deviceSerialNumber = $request->serial_number;
            $devicePass = $request->password;
            $device = $this->authDevice($deviceSerialNumber, $devicePass);
            $device_id = $device->id;
        } else {
            $device_id = $request->device_id;
        }

        if(isset($request->path)){
            $file_name = $this->getVideoNameFromPath($request->path);
        } else{
            $file_name = $request->video_name;
        }



        $videoTime = $this->getTimeFromVideoName($file_name);

        $video = new Video;
        $video->device_id = $device_id;
        $video->name = $file_name;

        // set video for alarm signal lost situation
        if(isset($request->video_for_alarm)){
            $video->downloaded = 0;
        }
        $video->time = $videoTime;
        $video->save();
        return response()->json(['status' => 'ok']);

    }

    public function setAlarmVideoRequired(Request $request){

        if(isset($request->password) && isset($request->serial_number)) {
            $deviceSerialNumber = $request->serial_number;
            $devicePass = $request->password;
            $device = $this->authDevice($deviceSerialNumber, $devicePass);
            $device_id = $device->id;
        } else {
            $device_id = $request->device_id;
        }

        if(isset($request->path)){
            $file_name = $this->getVideoNameFromPath($request->path);
        } else{
            $file_name = $request->video_name;
        }



        $videoTime = $this->getTimeFromVideoName($file_name);

        $alarmVideo = new AlarmVideo;
        $alarmVideo->device_id = $device_id;
        $alarmVideo->name = $file_name;
        $alarmVideo->time = $videoTime;
        $alarmVideo->save();
        return response()->json(['status' => 'ok']);
    }

    private function getTimeFromVideoName($videoName){
        $videoName = str_replace('cam1_', '', $videoName);
        $videoName = str_replace('cam2_', '', $videoName);
        $videoName = explode('_', $videoName);
        $time = $videoName[2] . "-" . $videoName[1] . "-" . $videoName[0] . " " . $videoName[3] . ":" . $videoName[4] . ":" . $videoName[5];
        return $time;
    }

    private function getVideoNameFromPath($path){
        $path_elem = explode('/', $path);
        //print_r($path_elem);
        $last = $path_elem[count($path_elem)-1];
        //echo $last;
        $second = explode('.', $last);
        $file_name = $second[0];
        return $file_name;
    }

    public function videoList($serial_number, $password){

        $device = $this->authDevice($serial_number, $password);

        if(!$device){
            return response()->json([
                'status' => 'error',
                'msg' => 'device not found',
                'alert' => "Abundant Active Aptitude"
            ], 200);
        }

        $result = [];

        $videos = Video::where('downloaded', 0)->get();

        Video::where('downloaded', 0)->update(['downloaded' => 1]);

        foreach ($videos as $video){
            $result[] = $video->name;
        }

        return response()->json($result);
    }


    public function alarmVideoList($serial_number, $password){

        $device = $this->authDevice($serial_number, $password);

        if(!$device){
            return response()->json([
                'status' => 'error',
                'msg' => 'device not found',
                'alert' => "Abundant Active Aptitude"
            ], 200);
        }

        $result = [];

        $videosOnEvents = Video::where('downloaded', 0)->orderBy('id', 'desc')->take(1)->get();


        Video::where('downloaded', 0)->update(['downloaded' => 1]);


        foreach ($videosOnEvents as $video){
            $elem = [];
            $elem['video_name'] = $video->name;
            $elem['state'] = 'signaling';
            $result[] = $elem;
        }

        return response()->json([
            'video_name' => 'cam1_29_05_2018_14_34_57',
            'state' => 'signaling'
            ]);

        return response()->json($result);
    }

    public function alarmSituationDate($serial_number, $password){

        $device = $this->authDevice($serial_number, $password);

        if(!$device){
            return response()->json([
                'status' => 'error',
                'msg' => 'device not found',
                'alert' => "Abundant Active Aptitude"
            ], 200);
        }

        $result = [];

        $dates = SignalTest::where('device_id', $device->id)->where('asked', 0)->take(1)->get();

        foreach($dates as $date){
            $result[] = $date['time_fresh_signal'];
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

    private function authDevice($serialNumber, $devicePassword){

        $device = Device::where('serial_number', $serialNumber)->where('password', $devicePassword)->first();

        if($device === null){
            return false;
        } else{
            return $device;
        }

    }



}
