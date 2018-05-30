<?php

namespace App\Http\Controllers;

use App\{
    Device, Affiliation, Image, SignalTest, Zone
};
use Illuminate\{Http\Request, Support\Facades\Auth};
use Carbon\Carbon;


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



    public function checkAlarm(Request $request){

        $devicesAlarm = $this->getAllDevicesOnAlarm();
        foreach($devicesAlarm as $device){

            $currentCoordinates = $this->getLastCoordinates($device);

            $key = 'alarm' . $device->id;
            $alarmCoordinates = $request->session()->get($key);

            if(!isset($alarmCoordinates['lat'])){
                return;
            }

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



    public function getAffiliatedDevices(){
        $current_user = Auth::id();
        $getAffiliatedDevices = Affiliation::where('affiliate_user_id', $current_user)->where('status', 1)->get();
        $devices = [];
        foreach ($getAffiliatedDevices as $getAffiliatedDevice){
            $device = $getAffiliatedDevice;
            $zone  = Zone::find($device->zone_id);

            $devices[] = $device->getDevice();
        }
        return $devices;
    }

    public function bondDevicesOnConnection(){
        $getDevices = Device::all();
        $nowTime = Carbon::now()->timestamp;

        foreach($getDevices as $device){
            $timeOfLastConnection = $this->getLastConnectionTime($device);
            if(!is_null($timeOfLastConnection)){
                $timeOutSignal = $nowTime - $timeOfLastConnection;
                if(!$this->checkValidConnection($timeOutSignal)){
                    continue;
                }

                $this->informNearblyDevicesAboutAlarm($device, $timeOfLastConnection);
            }
        }

        return response()->json(['status' => 'ok']);
    }

    private function informNearblyDevicesAboutAlarm($device, $timeOfLastConnection){
        $nearblyDevices = $this->findAllDevicesInRadius($device, 500);

        foreach($nearblyDevices as $nearblyDevice){
            $this->createSignalAlarm($nearblyDevice, $timeOfLastConnection);
        }

        return response()->json(['status' => 'ok']);
    }

    private function createSignalAlarm($nearblyDevice, $timeOfLastConnection){
        $signalTest = new SignalTest;
        $deviceOwner = $nearblyDevice->getDeviceBigNoise()->first();
        $signalTest->user_id = $deviceOwner->id;
        $signalTest->device_id = $nearblyDevice->id;
        $signalTest->time_fresh_signal = date('Y-m-d H:i:s', $timeOfLastConnection);
        $signalTest->unix_time_fresh_signal = $timeOfLastConnection;
        $signalTest->save();
    }

    private function checkValidConnection($timeOutSignal){
        return true;
        if($timeOutSignal < 30) {
            return true;
        }
        return false;
    }

    private function findAllDevicesInRadius($device, $radius){
        $CurDevLngLat = $device->locationLatLng();

        $getDevices = Device::all();
        $result = [];

        foreach ($getDevices as $single_device){
            if($single_device->id == $device->id){
                continue;
            }

            $curDevLngLat = $single_device->locationLatLng();

            if(is_null($curDevLngLat)){
                continue;
            }

            $distanceBetwenDevices = $this->distanceByPath($CurDevLngLat, $curDevLngLat);
            if($distanceBetwenDevices < $radius){
                $result[] = $single_device;
            }
        }
        return $result;
    }

    private function getLastConnectionTime($device){
        $dev_id = $device->id;
        //echo $dev_id;
        $timeDataByImageGet = Image::where('device_id', $dev_id)->orderBy('created_at', 'desc')->first();
        if(is_null($timeDataByImageGet)){
            return null;
        }

        //print_r($timeDataByImageGet);
        //die();
        return $timeDataByImageGet->created_at->timestamp;
    }

    private function getAllDevicesOnAlarm(){
        $user_id = Auth::id();
        $alarmDevices = Device::where('user_id', $user_id)->where('alarm_system', 1)->get();
        return $alarmDevices;
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

    private function checkPointInZone($point1, $point2, $radius){
        $distance = $this->distance($point1['lat'], $point1['lon'], $point2['lat'], $point2['lng']);
        if($distance > $radius){
            return false;
        } else {
            return true;
        }
    }

    private function distance($lat1, $lon1, $lat2, $lon2) {

      if($lat1 === $lat2 && $lon1 === $lon2){
        return 0;
      }

      $theta = $lon1 - $lon2;
      $dist = sin(deg2rad($lat1)) * sin(deg2rad($lat2)) +  cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * cos(deg2rad($theta));

      $dist = acos($dist);

      $dist = rad2deg($dist);
      $miles = $dist * 60 * 1.1515;
      return ($miles * 1.609344)*1000;
    }

    private function distanceByPath($path1, $path2){

        if(!$this->checkPath($path1) || !$this->checkPath($path2)){
           return null;
        }


        $lat1 = $path1->latitude;
        $lon1 = $path1->longitude;
        $lat2 = $path2->latitude;
        $lon2 = $path2->longitude;

        return $this->distance($lat1, $lon1, $lat2, $lon2);
    }

    private function checkPath($path){
        if(is_null($path)){
            return false;
        }
        return true;
    }






}
