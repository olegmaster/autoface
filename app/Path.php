<?php

namespace App;

use Illuminate\Database\Eloquent\Model;


class Path extends Model
{



    public function getAddressByCoordinates(){
        $url = 'https://maps.googleapis.com/maps/api/geocode/json?language=ru&latlng=';
        $url .= $this->latitude . ',' . $this->longitude;
        $url .= '&key=' . config('app.google_geocoding_api_key');
        $response = $url;
        $data_str = file_get_contents($url);
        $data = json_decode($data_str);
        //print_r($data->results[0]->address_components[0]);

        if(!isset($data->results[0]->address_components[3])){
            return '';
        }



        $ad1 = isset($data->results[0]->address_components[1]) ? $data->results[0]->address_components[1] : '';
        $ad2 = isset($data->results[0]->address_components[0]) ? $data->results[0]->address_components[0] : '';
        $ad3 = isset($data->results[0]->address_components[3]) ? $data->results[0]->address_components[3] : '';
        $ad4 = isset($data->results[0]->address_components[5]) ? $data->results[0]->address_components[5] : '';
        $ad5 = isset($data->results[0]->address_components[6]) ? $data->results[0]->address_components[6] : '';
        $ad6 = isset($data->results[0]->address_components[7]) ? $data->results[0]->address_components[7] : '';


        $address = '';

        try{
            $address = $ad1->long_name . ', '
                . $ad2->long_name . ','
                . $ad3->long_name . ','
                . $ad4->long_name . ','
                . $ad5->long_name . ','
                . $ad6->long_name;
        }
        catch(Exception $e){
            $address = '';
        }


        return $address;
    }

    public function images(){
        return $this->hasMany('App\Image');
    }


}
