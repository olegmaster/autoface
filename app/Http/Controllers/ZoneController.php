<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Zone;

class ZoneController extends Controller
{
    public function add(Request $request){
        $zone = new Zone;
        $zone->device_id = $request->device_id;
        $zone->longitude = $request->longitude;
        $zone->latitude = $request->latitude;
        $zone->radius = $request->radius;
        $zone->save();
        return response()->json([
            'status' => 'success',
            'id' => $zone->id
        ]);
    }

    public function change(Request $request){
        $zone = Zone::find($request->id);
        $zone->device_id = $request->device_id;
        $zone->longitude = $request->longitude;
        

    }
}
