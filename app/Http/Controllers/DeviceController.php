<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Device;
use Illuminate\Support\Facades\Auth;


class DeviceController extends Controller
{
    public function __construct(){

        $this->middleware('auth');
    }

    public function index(){
        $user_id = Auth::id();
        $devices = Device::where('user_id', $user_id)->paginate(3);
        return view('device.index', ['devices' => $devices]);
    }

    public function create(){
        return view('device.create');
    }

    public function store(Request $request){
        $device = new Device;
        $device->user_id = Auth::id();
        $device->serial_number = $request->serial_number;
        $device->password = $request->password;
        $device->vehicle = $request->vehicle;
        $device->device_type = $request->device_type;
        $device->personal_number = $request->personal_number;
        $device->alarm_system = $request->alarm_system;
        $device->save();


        return redirect('/device');
    }

    public function show($id){
        echo 'ecfo';
    }

    public function edit($id){
        $device = Device::where('id', $id)->first();
        return view('device.edit', ['device' => $device]);
    }

    public function update(Request $request, $id){
        $device = Device::find($id);
        $device->user_id = Auth::id();
        $device->serial_number = $request->serial_number;
        $device->password = $request->password;
        $device->vehicle = $request->vehicle;
        $device->device_type = $request->device_type;
        $device->personal_number = $request->personal_number;
        $device->alarm_system = $request->alarm_system;
        $device->save();
        return redirect('/device');

    }

    public function destroy($id){
        Device::destroy($id);
        return redirect('/device');
    }


    public function getPathOfDevice($DeviceId){
        $device = new Device;
        $device->id = $DeviceId;
        $paths = $device->obtainPath()->orderBy('id', 'desc')->take(10000)->get();
        foreach ($paths as $path){
            echo "<pre>";
            echo $path->latitude;
            //print_r($path);
            echo "</pre>";
        }

        return ;
    }


}
