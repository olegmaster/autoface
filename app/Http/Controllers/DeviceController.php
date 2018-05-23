<?php

namespace App\Http\Controllers;

use App\Device;
use Illuminate\{Http\Request, Support\Facades\Auth};


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
        echo 'wecfo';
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

    public function getDeviceForMap($id){
        $device = Device::where('id', $id)->first();
        return response()->json($device->locationLatLng());
    }

    public function getAllDevices(){
        $user_id = Auth::id();
        $coordinates = [];
        $devices = Device::where('user_id', $user_id)->get();

        foreach ($devices as $device){
            $coordinates[] = $device->locationLatLng();
        }

        return response()->json($coordinates);
    }

    public function putDeviceOnAlarm(Request $request) {

         $device = Device::find($request->id);
         $path = $device->locationLatLng();
         $latLngFromPath = [
             'lat' => $path->latitude,
             'lng' => $path->longitude
         ];

         $key = 'alarm' . $request->id;

         $request->session()->put($key, $latLngFromPath);

         $device->alarm_system = 1;

         $device->save();
         return response()->json($latLngFromPath);

    }

    public function takeOfDeviceFromAlarm(Request $request){

        $device = $this->getDevice($request->id);

        $currentDeviceCoordinates = $this->getLastCoordinates($device);

        $key = 'alarm' . $request->id;

        $request->session()->forget($key);

        $device->alarm_system = 0;

        $device->save();
        return response()->json($currentDeviceCoordinates);
    }

    private function getDevice($id){
        return Device::find($id);
    }

    private function getLastCoordinates(Device $device){
        $path = $device->locationLatLng();

        return [
            'lat' => $path->latitude,
            'lng' => $path->longitude
        ];
    }

    public function checkAlarm(Request $request){

        $devicesAlarm = $this->getAllDevicesOnAlarm();
        foreach($devicesAlarm as $device){
            $currentCoordinates = $this->getLastCoordinates($device);
            $key = 'alarm' . $device->id;
            $alarmCoordinates = $request->session()->get($key);
            if($currentCoordinates['lat'] !== $alarmCoordinates['lat'] || $currentCoordinates['lng'] !== $alarmCoordinates['lng']){
                return response()->json([
                    'status' => 'alarm',
                    'device_name' => $device->vehicle,
                    'device_id' => $device->id
                ]);
            }
        }

        return response()->json([
            'status' => 'ok'
        ]);




    }

    private function getAllDevicesOnAlarm(){
        $user_id = Auth::id();
        $alarmDevices = Device::where('user_id', $user_id)->where('alarm_system', 1)->get();
        return $alarmDevices;
    }





}
