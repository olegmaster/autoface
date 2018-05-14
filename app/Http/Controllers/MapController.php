<?php

namespace App\Http\Controllers;

use App\Device;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use App\User;

class MapController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user_id = Auth::id();
        $user = new User;
        $user->id = $user_id;
        $devices = $user->obtainDevices()->get();

        $locations = $this->getLocationsOfDevices($devices);

        return view('map',['devices' => $devices, 'locations' => $locations]);
    }


    private function getLocationsOfDevices($devices):array {
        $locations = [];
        foreach ($devices as $device){
            $path = $device->obtainPath();
            $devLocation = $path->orderBy('id', 'desc')->first();
            if(!empty($devLocation)){
                $locations[] = ['name' => $device->vehicle,'latitude' =>$devLocation->latitude, 'longitude' => $devLocation->longitude];
            }
        }
        return $locations;
    }






}
