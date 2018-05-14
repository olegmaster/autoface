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

        return $data->results[0]->address_components[1]->long_name . ', '
            . $data->results[0]->address_components[0]->long_name . ','
            . $data->results[0]->address_components[3]->long_name . ','
            . $data->results[0]->address_components[5]->long_name . ','
            . $data->results[0]->address_components[6]->long_name . ','
            . $data->results[0]->address_components[7]->long_name;
    }
}
