<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Path;
use App\Image;
use App\Device;

class ImageController extends Controller
{
    public function getImage(Request $request){

        $deviseId = $request->device_id;
        $cameraNum = $request->camera;
        $page = $request->page;
        $fromTime = $request->from_time;
        $toTime = $request->to_time;


        $skip = 5 * $page - 5;

        $images = Image::where('device_id', $deviseId)
            ->where('camera_number', $cameraNum)
            ->where('time', '>', $fromTime)
            ->where('time', '<', $toTime)
            ->orderBy('id', 'desc')
            ->take(5)
            ->skip($skip)
            ->get();
        $imagesArr = $images->toArray();


        return response()->json(array('images' => $imagesArr), 200);
    }
}
