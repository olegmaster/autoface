<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Path;
use App\Image;
use App\Device;

class ImageController extends Controller
{
    public function getImage($deviseId, $cameraNum, $page){
        $msg = "This is a simple message" . $page;
        $images = [];
        $skip = 5 * $page - 5;
        $paths = Path::where('device_id', $deviseId)->orderBy('id', 'desc')->take(5)->skip($skip)->get();
        foreach($paths as $path) {
            $img = $path->images->where('camera_number', $cameraNum)->first();
            if($img){
//                echo "<pre>";
//                print_r($path->images->where('camera_number', $cameraNum)->first());
//                echo "/data/images/" . $deviseId . "/" . $img->id . ".jpg";
//                echo "</pre>";
                $images[] = [
                    'id' => $img->id,
                    'path' => "/data/images/" . $deviseId . "/" . $img->id . ".jpg"
                    ];
            }

        }

        return response()->json(array('images' => $images), 200);
    }
}
