<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ApiController extends Controller
{
    public function imageSave(Request $request){

        $file = $request->file('image');
        $deviceSerialNumber = $request->serial_number;
        $devicePass = $request->password;
        echo $deviceSerialNumber;
        echo $devicePass;
        if(isset($file)){

            echo "<pre>";
            echo "I have recived an image";
            //print_r($request->file('image'));
            $path = $request->file('image')->store('data');
            echo $path;
            echo "</pre>";
        }

        echo "22";

        //return response()->json(array('status' => 'ok'), 200);
    }
}
