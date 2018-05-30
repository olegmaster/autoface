<?php

namespace App\Http\Controllers;

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

    public function view($id){
        $alarm_situation = SignalTest::find($id);
        $alarm_time_val = $alarm_situation->time_fresh_signal;
        $getVideos = Video::where('device_id', $alarm_situation->device_id)->where('time', '>', $alarm_time_val)->take(6);

    }
}
