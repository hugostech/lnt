<?php

namespace App;

use GuzzleHttp\Client;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    static function getProductBySku($sku){
        $client = new Client([
            // Base URI is used with relative requests
            'base_uri' => config('lnt_connect'),
            // You can set any number of default request options.
            'timeout'  => 2.0,
        ]);
        $response = $client->get('getProductInfo/'.$sku);
        if ($response->getStatusCode()==200){
            return json_decode($response->getBody()->getContents(),true);
        }else{
            return false;
        }
    }
}
