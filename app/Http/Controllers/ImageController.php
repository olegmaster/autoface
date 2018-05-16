<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Path;
use App\Image;
use App\Device;

class ImageController extends Controller
{
    public function getImage($deviseId, $cameraNum, $page){

        $skip = 5 * $page - 5;

        $images = Image::where('device_id', $deviseId)->where('camera_number', $cameraNum)->orderBy('id', 'desc')->take(5)->skip($skip)->get();
        $imagesArr = $images->toArray();

        //print_r($imagesArr);

        //die();




        return response()->json(array('images' => $imagesArr), 200);
    }
}
