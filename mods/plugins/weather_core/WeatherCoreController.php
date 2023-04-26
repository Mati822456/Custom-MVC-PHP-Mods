<?php

namespace App\Controllers;

use App\Controllers\Controller;

class WeatherCoreController extends Controller{

    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://api.open-meteo.com/v1/forecast?latitude=52.23&longitude=21.01&current_weather=true&forecast_days=1&timezone=auto",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_HTTPHEADER => array(
              "cache-control: no-cache"
            ),
        ));
          
        $response = curl_exec($curl);
        $err = curl_error($curl);
          
        curl_close($curl);

        print_r($response);
    }

}