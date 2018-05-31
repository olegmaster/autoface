<?php

namespace App\Http\Controllers;

use App\AlarmVideo;
use App\Video;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\SignalTest;

class AlarmSituationController extends Controller
{
    public function index(){
        $currentUserId = Auth::id();
        $alarm_situations = SignalTest::where('user_id', $currentUserId)->paginate(5);
        return view('alarm_situation.index', ['alarm_situations' => $alarm_situations]);
    }

    public function show($id){
        $alarm_situation = SignalTest::find($id);
        $alarm_time_val = $alarm_situation->time_fresh_signal;
        $alarm_time_minus_minute = $this->subtractNMinutesFromStrDate($alarm_time_val, 1);

        $getAlarmVideos = AlarmVideo::where('device_id', $alarm_situation->device_id)->where('time', '>', $alarm_time_minus_minute)->take(12)->get();

        if(!is_null($getAlarmVideos)){
            foreach($getAlarmVideos as $alarmVideo){
                if($alarmVideo->downloaded == 1){
                    continue;
                }
                $this->copyAlarmVideoToVideo($alarmVideo);
                $alarmVideo->downloaded = 1;
                $alarmVideo->save();
            }
        }

        return view('alarm_situation.show', ['videos' => $getAlarmVideos]);
    }

    private function subtractNMinutesFromStrDate($date, $minutesCount){
        $unixTime = strtotime($date);
        $resultTime = $unixTime - $minutesCount * 60;
        return date('Y-m-d H:i:s', $resultTime);
    }

    private function copyAlarmVideoToVideo($alarmVideo){
        $video = new Video;
        $video->device_id = $alarmVideo->device_id;
        $video->name = $alarmVideo->name;
        $video->downloaded = 0;
        $video->time = $alarmVideo->time;
        $video->save();
    }
}
